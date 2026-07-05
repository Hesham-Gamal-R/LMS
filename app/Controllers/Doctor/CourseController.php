<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Course.php';
require_once __DIR__ . '/../../../app/Models/Doctor.php';

class CourseController extends Controller {

    public function index(): void {
        $this->requireAuth('doctor');
        $dm      = new Doctor();
        $courses = $dm->getCourses(currentUserId());
        $this->view('doctor.courses', ['title' => 'My Courses', 'courses' => $courses]);
    }

    public function create(): void {
        $this->requireAuth('doctor');
        $this->view('doctor.course_form', ['title' => 'Create Course', 'course' => null]);
    }

    public function store(): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $code        = sanitize($this->post('code'));
        $name        = sanitize($this->post('name'));
        $description = sanitize($this->post('description'));
        $creditHours = (int)$this->post('credit_hours');
        $status      = $this->post('status', 'active');

        $errors = [];
        if (empty($code))  $errors[] = 'Course code is required.';
        if (empty($name))  $errors[] = 'Course name is required.';
        if ($creditHours < 1 || $creditHours > 10) $errors[] = 'Credit hours must be between 1 and 10.';
        if (!in_array($status, ['active','inactive'])) $status = 'active';

        $cm = new Course();
        if ($cm->codeExists($code)) $errors[] = 'Course code already exists.';

        if (!empty($errors)) {
            $this->view('doctor.course_form', ['title' => 'Create Course', 'course' => null, 'errors' => $errors,
                'old' => compact('code','name','description','creditHours','status')]);
            return;
        }

        $id = $cm->insert([
            'code'         => $code,
            'name'         => $name,
            'description'  => $description,
            'doctor_id'    => currentUserId(),
            'credit_hours' => $creditHours,
            'status'       => $status,
        ]);

        $this->flashSuccess('Course created successfully.');
        $this->redirect('/doctor/course/' . $id);
    }

    public function show(array $params): void {
        $this->requireAuth('doctor');
        $courseId = (int)$params['id'];
        $cm       = new Course();
        $course   = $cm->getWithDoctor($courseId);

        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->flashError('Course not found or access denied.');
            $this->redirect('/doctor/courses');
        }

        $contents  = $cm->getContents($courseId);
        $students  = $cm->getStudents($courseId);
        $announcements = $cm->getAnnouncements($courseId);

        require_once __DIR__ . '/../../../app/Models/StudentQuestion.php';
        $qm        = new StudentQuestion();
        $questions = $qm->getForCourse($courseId);

        $this->view('doctor.course_detail', [
            'title'         => $course['name'],
            'course'        => $course,
            'contents'      => $contents,
            'students'      => $students,
            'announcements' => $announcements,
            'questions'     => $questions,
        ]);
    }

    public function edit(array $params): void {
        $this->requireAuth('doctor');
        $courseId = (int)$params['id'];
        $cm       = new Course();
        $course   = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }
        $this->view('doctor.course_form', ['title' => 'Edit Course', 'course' => $course]);
    }

    public function update(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $courseId    = (int)$params['id'];
        $cm          = new Course();
        $course      = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        $code        = sanitize($this->post('code'));
        $name        = sanitize($this->post('name'));
        $description = sanitize($this->post('description'));
        $creditHours = (int)$this->post('credit_hours');
        $status      = $this->post('status', 'active');

        $errors = [];
        if (empty($code)) $errors[] = 'Course code is required.';
        if (empty($name)) $errors[] = 'Course name is required.';
        if ($creditHours < 1 || $creditHours > 10) $errors[] = 'Credit hours must be between 1 and 10.';
        if ($cm->codeExists($code, $courseId)) $errors[] = 'Course code already taken.';

        if (!empty($errors)) {
            $this->view('doctor.course_form', ['title' => 'Edit Course', 'course' => $course, 'errors' => $errors]);
            return;
        }

        $cm->update($courseId, [
            'code' => $code, 'name' => $name, 'description' => $description,
            'credit_hours' => $creditHours, 'status' => $status,
        ]);

        $this->flashSuccess('Course updated successfully.');
        $this->redirect('/doctor/course/' . $courseId);
    }

    public function destroy(array $params): void {
        $this->requireAuth('doctor');
        if (!$this->isPost()) { $this->redirect('/doctor/courses'); }

        $courseId = (int)$params['id'];
        $cm       = new Course();
        $course   = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }

        if (!$cm->canDelete($courseId)) {
            $this->flashError('Cannot delete a course that has enrolled students.');
            $this->redirect('/doctor/course/' . $courseId);
            return;
        }

        $cm->delete($courseId);
        $this->flashSuccess('Course deleted successfully.');
        $this->redirect('/doctor/courses');
    }

    public function students(array $params): void {
        $this->requireAuth('doctor');
        $courseId = (int)$params['id'];
        $cm       = new Course();
        $course   = $cm->find($courseId);
        if (!$course || $course['doctor_id'] != currentUserId()) {
            $this->redirect('/doctor/courses');
        }
        $students = $cm->getStudents($courseId);
        $this->view('doctor.students_list', ['title' => 'Enrolled Students — ' . $course['name'], 'course' => $course, 'students' => $students]);
    }
}
