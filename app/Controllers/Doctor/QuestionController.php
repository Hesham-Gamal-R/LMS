<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/StudentQuestion.php';
require_once __DIR__ . '/../../../app/Models/Doctor.php';

class QuestionController extends Controller {
    public function index(): void {
        $this->requireAuth('doctor');
        $qm        = new StudentQuestion();
        $questions = $qm->getForDoctor(currentUserId());
        $this->view('doctor.questions', ['title' => 'Student Questions', 'questions' => $questions]);
    }

    public function answer(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/questions'); }

        $questionId = (int)$params['id'];
        $qm         = new StudentQuestion();
        $question   = $qm->getWithDetails($questionId);

        if (!$question) { $this->redirect('/doctor/questions'); }

        // Verify this doctor owns the course
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT doctor_id FROM courses WHERE id = ?");
        $stmt->execute([$question['course_id']]);
        $row = $stmt->fetch();
        if (!$row || $row['doctor_id'] != currentUserId()) {
            $this->flashError('Access denied.');
            $this->redirect('/doctor/questions');
        }

        $answerText = sanitize($this->post('answer_text'));
        if (empty($answerText)) {
            $this->flashError('Answer cannot be empty.');
            $this->redirect('/doctor/questions');
            return;
        }

        $qm->answer($questionId, currentUserId(), $answerText);
        $this->flashSuccess('Answer submitted successfully.');
        $this->redirect('/doctor/questions');
    }
}
