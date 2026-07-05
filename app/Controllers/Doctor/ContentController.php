<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Content.php';
require_once __DIR__ . '/../../../app/Models/Course.php';

class ContentController extends Controller {

    public function store(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $courseId = (int)$params['id'];
        $cm = new Course();
        $course = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        $title       = sanitize($this->post('title'));
        $description = sanitize($this->post('description', ''));
        $type        = $this->post('type', 'pdf');

        $errors = [];
        if (empty($title))            $errors[] = 'Title is required.';
        if (!in_array($type, ['pdf','video'])) $errors[] = 'Invalid content type.';
        if (empty($_FILES['file']['name'])) $errors[] = 'File is required.';

        if (!empty($errors)) {
            $this->flashError(implode(' ', $errors));
            $this->redirect('/doctor/course/' . $courseId);
            return;
        }

        $subdir = $type === 'pdf' ? 'pdfs' : 'videos';
        $path   = $this->uploadFile($_FILES['file'], $subdir);
        if (!$path) {
            $this->flashError('File upload failed. Check file type and size.');
            $this->redirect('/doctor/course/' . $courseId);
            return;
        }

        $contModel = new Content();
        $order     = $contModel->getNextOrder($courseId);
        $contModel->insert([
            'course_id'   => $courseId,
            'title'       => $title,
            'description' => $description,
            'type'        => $type,
            'file_path'   => $path,
            'order_num'   => $order,
        ]);

        $this->flashSuccess('Content added successfully.');
        $this->redirect('/doctor/course/' . $courseId);
    }

    public function editForm(array $params): void {
        $this->requireAuth('doctor');
        $contentId = (int)$params['id'];
        $contModel = new Content();
        $content   = $contModel->getWithCourse($contentId);
        if (!$content || $content['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }
        $this->view('doctor.content_edit', ['title' => 'Edit Content', 'content' => $content]);
    }

    public function update(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $contentId = (int)$params['id'];
        $contModel = new Content();
        $content   = $contModel->getWithCourse($contentId);
        if (!$content || $content['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        $title       = sanitize($this->post('title'));
        $description = sanitize($this->post('description', ''));
        $orderNum    = (int)$this->post('order_num', $content['order_num']);

        if (empty($title)) {
            $this->flashError('Title is required.');
            $this->redirect('/doctor/content/' . $contentId . '/edit');
            return;
        }

        $data = ['title' => $title, 'description' => $description, 'order_num' => max(1, $orderNum)];

        // Optional file replacement
        if (!empty($_FILES['file']['name'])) {
            $subdir = $content['type'] === 'pdf' ? 'pdfs' : 'videos';
            $path   = $this->uploadFile($_FILES['file'], $subdir);
            if ($path) {
                if (file_exists(UPLOAD_PATH . $content['file_path'])) {
                    unlink(UPLOAD_PATH . $content['file_path']);
                }
                $data['file_path'] = $path;
            }
        }

        $contModel->update($contentId, $data);
        $this->flashSuccess('Content updated.');
        $this->redirect('/doctor/course/' . $content['course_id']);
    }

    public function destroy(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $contentId = (int)$params['id'];
        $contModel = new Content();
        $content   = $contModel->getWithCourse($contentId);
        if (!$content || $content['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        if (file_exists(UPLOAD_PATH . $content['file_path'])) {
            unlink(UPLOAD_PATH . $content['file_path']);
        }
        $contModel->delete($contentId);
        $this->flashSuccess('Content deleted.');
        $this->redirect('/doctor/course/' . $content['course_id']);
    }
}
