<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Student.php';
require_once __DIR__ . '/../../../app/Models/Announcement.php';
require_once __DIR__ . '/../../../app/Models/StudentQuestion.php';

class DashboardController extends Controller {
    public function index(): void {
        $this->requireAuth('student');
        $studentId = currentUserId();
        $sm = new Student();
        $am = new Announcement();
        $qm = new StudentQuestion();

        $courses       = $sm->getEnrolledCourses($studentId);
        $announcements = $am->getForStudentCourses($studentId);
        $questions     = $qm->getForStudent($studentId);

        // Notifications
        $lastSeen      = $_SESSION['student_last_notif_seen'] ?? date('Y-m-d H:i:s', strtotime('-30 days'));
        $notifications = $qm->getNewAnsweredForStudent($studentId, $lastSeen);
        $notifCount    = count($notifications);
        $_SESSION['student_last_notif_seen'] = date('Y-m-d H:i:s');

        $this->view('student.dashboard', [
            'title'         => 'Student Dashboard',
            'courses'       => $courses,
            'announcements' => array_slice($announcements, 0, 5),
            'questions'     => $questions,
            'notifications' => $notifications,
            'notifCount'    => $notifCount,
        ]);
    }
}
