<?php
require_once __DIR__ . '/../../core/Model.php';

class Student extends Model {
    protected string $table = 'students';

public function findByEmail(string $email): array|false {
    return $this->findOne("email = '$email'");
}

public function findByNationalId(string $nid): array|false {
    return $this->findOne("national_id = '$nid'");
}

public function getEnrolledCourses(int $studentId): array {
    return $this->query(
        "SELECT c.*, d.name AS doctor_name, d.department AS doctor_dept, e.enrolled_at,
                (SELECT COUNT(*) FROM contents WHERE course_id = c.id) AS content_count
         FROM courses c
         JOIN enrollments e ON e.course_id = c.id
         JOIN doctors d ON d.id = c.doctor_id
         WHERE e.student_id = $studentId AND c.status = 'active'
         ORDER BY e.enrolled_at DESC"
    );
}

public function isEnrolled(int $studentId, int $courseId): bool {
    $r = $this->queryOne("SELECT 1 FROM enrollments WHERE student_id = $studentId AND course_id = $courseId");
    return (bool)$r;
}

public function enroll(int $studentId, int $courseId): bool {
    return $this->execute("INSERT IGNORE INTO enrollments (student_id, course_id) VALUES ($studentId, $courseId)");
}

public function unenroll(int $studentId, int $courseId): bool {
    return $this->execute("DELETE FROM enrollments WHERE student_id = $studentId AND course_id = $courseId");
}

public function getUnreadNotifications(int $studentId): array {
    $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
    return $this->query(
        "SELECT sq.*, c.name AS course_name, d.name AS doctor_name
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         JOIN doctors d ON d.id = sq.answered_by
         WHERE sq.student_id = $studentId 
           AND sq.status = 'answered' 
           AND sq.answered_at >= '$sevenDaysAgo'
         ORDER BY sq.answered_at DESC"
    );
}

public function getNewNotificationsCount(int $studentId): int {
    $lastSeen = $_SESSION['student_last_notif_seen'] ?? date('Y-m-d H:i:s', strtotime('-30 days'));
    $r = $this->queryOne(
        "SELECT COUNT(*) as cnt 
         FROM student_questions
         WHERE student_id = $studentId 
           AND status = 'answered' 
           AND answered_at > '$lastSeen'"
    );
    return (int)($r['cnt'] ?? 0);
}
}
