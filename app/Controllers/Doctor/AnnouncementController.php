<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Announcement.php';
require_once __DIR__ . '/../../../app/Models/Course.php';
 
class AnnouncementController extends Controller {

    public function store(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $courseId = (int)$params['id'];
        $cm       = new Course();
        $course   = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        $title = sanitize($this->post('title'));
        $body  = sanitize($this->post('body'));
        $type  = $this->post('type', 'general');

        $errors = [];
        if (empty($title)) $errors[] = 'Title is required.';
        if (empty($body))  $errors[] = 'Body is required.';
        if (!in_array($type, ['general','assignment','exam','other'])) $type = 'general';

        if (!empty($errors)) {
            $this->flashError(implode(' ', $errors));
            $this->redirect('/doctor/course/' . $courseId);
            return;
        }

        $am = new Announcement();
        $am->insert([
            'course_id' => $courseId,
            'doctor_id' => currentUserId(),
            'title'     => $title,
            'body'      => $body,
            'type'      => $type,
        ]);

        $this->flashSuccess('Announcement posted successfully.');
        $this->redirect('/doctor/course/' . $courseId);
    }

    public function destroy(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $id = (int)$params['id'];
        $am = new Announcement();
        $ann = $am->find($id);
        if (!$ann || $ann['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }
        $courseId = $ann['course_id'];
        $am->delete($id);
        $this->flashSuccess('Announcement deleted.');
        $this->redirect('/doctor/course/' . $courseId);
    }
}
