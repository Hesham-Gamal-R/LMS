<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../app/Models/Course.php';
require_once __DIR__ . '/../../app/Models/Doctor.php';

class HomeController extends Controller {

    public function index(): void {
        $courseModel = new Course();
        $doctorModel = new Doctor();
        $courses     = $courseModel->getAllWithDoctor();

        $doctorCount = $doctorModel->count();

        $this->view('home.index', [
            'title'       => 'UniLearn — University Learning Management System',
            'courses'     => $courses,
            'search'      => '',
            'doctorCount' => $doctorCount,
        ], 'public');
    }

    public function courses(): void {
        $courseModel = new Course();
        $search      = sanitize($this->get('q', ''));
        $courses     = $courseModel->getAllWithDoctor($search);
        $this->view('home.courses', [
            'title'   => 'All Courses',
            'courses' => $courses,
            'search'  => $search,
        ], 'public');
    }

    public function courseDetail(): void {
        $id          = (int)($this->get('id', 0));
        $courseModel = new Course();
        $course      = $courseModel->getWithDoctor($id);
        if (!$course) { $this->redirect('/courses'); }

        $this->view('home.course_detail', [
            'title'  => $course['name'],
            'course' => $course,
        ], 'public');
    }
}
