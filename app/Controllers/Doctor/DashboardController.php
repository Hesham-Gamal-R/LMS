<?php
require_once __DIR__ . '/../../../core/Controller.php';
require_once __DIR__ . '/../../../app/Models/Doctor.php';
require_once __DIR__ . '/../../../app/Models/StudentQuestion.php';
require_once __DIR__ . '/../../../app/Models/Announcement.php';

class DashboardController extends Controller {
    public function index(): void {
        $this->requireAuth('doctor');
        $doctorId = currentUserId();
        $dm = new Doctor();
        $qm = new StudentQuestion();
        $am = new Announcement();

        $courses       = $dm->getCourses($doctorId);
        $questions     = $qm->getForDoctor($doctorId);
        $announcements = $am->getForDoctor($doctorId);

        // Notifications: new questions since last seen
        $lastSeen   = $_SESSION['doctor_last_notif_seen'] ?? date('Y-m-d H:i:s', strtotime('-30 days'));
        $notifCount = $dm->getNewQuestionsCount($doctorId);
        $_SESSION['doctor_last_notif_seen'] = date('Y-m-d H:i:s');

        $totalStudents = 0;
        foreach ($courses as $c) $totalStudents += $c['student_count'];

        $this->view('doctor.dashboard', [
            'title'         => 'Doctor Dashboard',
            'courses'       => $courses,
            'questions'     => array_slice($questions, 0, 5),
            'announcements' => array_slice($announcements, 0, 5),
            'notifCount'    => $notifCount,
            'totalStudents' => $totalStudents,
            'pendingCount'  => $dm->getPendingQuestionsCount($doctorId),
        ]);
    }
}
