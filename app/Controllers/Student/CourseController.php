<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Student.php';
require_once __DIR__ . '/../../../app/Models/Course.php';
require_once __DIR__ . '/../../../app/Models/Content.php';
require_once __DIR__ . '/../../../app/Models/Announcement.php';
require_once __DIR__ . '/../../../app/Models/StudentQuestion.php';

class CourseController extends Controller {

    public function index(): void {
        $this->requireAuth('student');
        $search  = sanitize($this->get('q', ''));
        $cm      = new Course();
        $sm      = new Student();
        $courses = $cm->getAllWithDoctor($search);
        $enrolled = [];
        foreach ($courses as $c) {
            if ($sm->isEnrolled(currentUserId(), $c['id'])) {
                $enrolled[$c['id']] = true;
            }
        }
        $this->view('student.courses', [
            'title'    => 'Browse Courses',
            'courses'  => $courses,
            'enrolled' => $enrolled,
            'search'   => $search,
        ]);
    }

    public function show(array $params): void {
        $this->requireAuth('student');
        $courseId  = (int)$params['id'];
        $studentId = currentUserId();
        $cm = new Course();
        $sm = new Student();

        $course = $cm->getWithDoctor($courseId);
        if (!$course || $course['status'] !== 'active') {
            $this->flashError('This course is not available.');
            $this->redirect('/student/dashboard');
        }

        if (!$sm->isEnrolled($studentId, $courseId)) {
            $this->flashError('You are not enrolled in this course.');
            $this->redirect('/student/courses');
        }

        $contModel   = new Content();
        $annModel    = new Announcement();
        $qModel      = new StudentQuestion();

        $contents      = $contModel->getForCourse($courseId);
        $announcements = $annModel->getByCourse($courseId);
        $questions     = $qModel->getForCourse($courseId);

        // Mark first unseen content as viewed
        foreach ($contents as $c) {
            if (!$contModel->isViewed($c['id'], $studentId)) {
                // do not auto-mark, only mark on explicit view
                break;
            }
        }

        $this->view('student.course_detail', [
            'title'        => $course['name'],
            'course'       => $course,
            'contents'     => $contents,
            'announcements'=> $announcements,
            'questions'    => $questions,
        ]);
    }

    public function enroll(array $params): void {
        $this->requireAuth('student');
        if (!$this->isPost()) { $this->redirect('/student/courses'); }
        $courseId  = (int)$params['id'];
        $studentId = currentUserId();
        $cm = new Course();
        $course = $cm->find($courseId);
        if (!$course || $course['status'] !== 'active') {
            $this->flashError('Cannot enroll in an inactive course.');
            $this->redirect('/student/courses');
            return;
        }
        $sm = new Student();
        if ($sm->isEnrolled($studentId, $courseId)) {
            $this->flashError('Already enrolled in this course.');
        } else {
            $sm->enroll($studentId, $courseId);
            $this->flashSuccess('Successfully enrolled in the course!');
        }
        $this->redirect('/student/courses');
    }

    public function unenroll(array $params): void {
        $this->requireAuth('student');
        if (!$this->isPost()) { $this->redirect('/student/courses'); }
        $courseId  = (int)$params['id'];
        $studentId = currentUserId();
        $sm = new Student();
        if ($sm->isEnrolled($studentId, $courseId)) {
            $sm->unenroll($studentId, $courseId);
            $this->flashSuccess('You have been unenrolled from the course.');
        }
        $this->redirect('/student/courses');
    }

    public function viewContent(array $params): void {
        $this->requireAuth('student');
        $contentId = (int)$params['id'];
        $studentId = currentUserId();
        $cm = new Content();
        $content = $cm->getWithCourse($contentId);
        if (!$content) { $this->redirect('/student/dashboard'); }

        // Block access if course is inactive
        if (($content['course_status'] ?? '') !== 'active') {
            $this->flashError('This course is not available.');
            $this->redirect('/student/dashboard');
        }

        $sm = new Student();
        if (!$sm->isEnrolled($studentId, $content['course_id'])) {
            $this->flashError('Access denied.');
            $this->redirect('/student/courses');
        }
        $cm->markViewed($contentId, $studentId);

        $filePath = UPLOAD_PATH . $content['file_path'];
        if (!file_exists($filePath)) {
            $this->flashError('File not found on server.');
            $this->redirect('/student/course/' . $content['course_id']);
        }
        $this->view('student.view_content', ['title' => $content['title'], 'content' => $content]);
    }

    public function downloadContent(array $params): void {
        $this->requireAuth('student');
        $contentId = (int)$params['id'];
        $studentId = currentUserId();
        $cm = new Content();
        $content = $cm->getWithCourse($contentId);
        if (!$content) { $this->redirect('/student/dashboard'); }

        // Block access if course is inactive
        if (($content['course_status'] ?? '') !== 'active') {
            http_response_code(403);
            die('Access denied.');
        }

        $sm = new Student();
        if (!$sm->isEnrolled($studentId, $content['course_id'])) {
            http_response_code(403);
            die('Access denied.');
        }
        $cm->markViewed($contentId, $studentId);

        $filePath = UPLOAD_PATH . $content['file_path'];
        if (!file_exists($filePath)) {
            $this->flashError('File not found.');
            $this->redirect('/student/course/' . $content['course_id']);
        }
        $mime = mime_content_type($filePath) ?: 'application/octet-stream';
        $filename = basename($content['file_path']);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $content['title'] . '.' . pathinfo($filename, PATHINFO_EXTENSION) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public function askQuestion(array $params): void {
        $this->requireAuth('student');
        if (!$this->isPost()) { $this->redirect('/student/dashboard'); }
        $courseId  = (int)$params['id'];
        $studentId = currentUserId();
        $cm = new Course();
        $course = $cm->find($courseId);
        if (!$course || $course['status'] !== 'active') {
            $this->flashError('This course is not available.');
            $this->redirect('/student/dashboard');
            return;
        }
        $sm = new Student();
        if (!$sm->isEnrolled($studentId, $courseId)) {
            $this->flashError('Access denied.');
            $this->redirect('/student/courses');
        }
        $text = sanitize($this->post('question_text'));
        if (empty($text)) {
            $this->flashError('Question cannot be empty.');
            $this->redirect('/student/course/' . $courseId);
            return;
        }
        $qm = new StudentQuestion();
        $qm->insert(['course_id' => $courseId, 'student_id' => $studentId, 'question_text' => $text]);
        $this->flashSuccess('Your question has been submitted.');
        $this->redirect('/student/course/' . $courseId);
    }
}
