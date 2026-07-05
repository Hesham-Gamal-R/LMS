<?php
require_once __DIR__ . '/../../core/Model.php';

class Announcement extends Model {
    protected string $table = 'announcements';

public function getForStudentCourses(int $studentId): array {
    return $this->query(
        "SELECT a.*, c.name AS course_name, d.name AS doctor_name
         FROM announcements a
         JOIN courses c ON c.id = a.course_id
         JOIN doctors d ON d.id = a.doctor_id
         WHERE c.status = 'active'
           AND a.course_id IN (SELECT course_id FROM enrollments WHERE student_id = $studentId)
         ORDER BY a.created_at DESC"
    );
}

public function getByCourse(int $courseId): array {
    return $this->query(
        "SELECT a.*, d.name AS doctor_name
         FROM announcements a 
         JOIN doctors d ON d.id = a.doctor_id
         WHERE a.course_id = $courseId 
         ORDER BY a.created_at DESC"
    );
}

public function getForDoctor(int $doctorId): array {
    return $this->query(
        "SELECT a.*, c.name AS course_name
         FROM announcements a 
         JOIN courses c ON c.id = a.course_id
         WHERE a.doctor_id = $doctorId 
         ORDER BY a.created_at DESC"
    );
}
}
