<?php
require_once __DIR__ . '/../../core/Model.php';

class Course extends Model {
    protected string $table = 'courses';

public function getAllWithDoctor(string $search = ''): array {
    $where = "c.status = 'active'";
    
    if ($search) {
        $where .= " AND d.name LIKE '%$search%'";
    }

    return $this->query(
        "SELECT c.*, d.name AS doctor_name, d.department AS doctor_dept, d.bio AS doctor_bio,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS student_count
         FROM courses c 
         JOIN doctors d ON d.id = c.doctor_id
         WHERE $where 
         ORDER BY c.created_at DESC"
    );
}

public function getWithDoctor(int $id): array|false {
    return $this->queryOne(
        "SELECT c.*, d.name AS doctor_name, d.department AS doctor_dept,
                d.bio AS doctor_bio, d.profile_image AS doctor_image,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS student_count
         FROM courses c 
         JOIN doctors d ON d.id = c.doctor_id 
         WHERE c.id = $id"
    );
}

public function canDelete(int $courseId): bool {
    $r = $this->queryOne("SELECT COUNT(*) as cnt FROM enrollments WHERE course_id = $courseId");
    return (int)($r['cnt'] ?? 0) === 0;
}

public function getStudents(int $courseId): array {
    return $this->query(
        "SELECT s.id, s.name, s.email, s.department, s.profile_image, e.enrolled_at
         FROM students s 
         JOIN enrollments e ON e.student_id = s.id
         WHERE e.course_id = $courseId 
         ORDER BY e.enrolled_at DESC"
    );
}

public function getContents(int $courseId): array {
    return $this->query(
        "SELECT c.*, 
                (SELECT COUNT(*) FROM content_views WHERE content_id = c.id) AS view_count
         FROM contents c 
         WHERE c.course_id = $courseId 
         ORDER BY c.order_num ASC, c.created_at ASC"
    );
}

public function getAnnouncements(int $courseId): array {
    return $this->query(
        "SELECT a.*, d.name AS doctor_name 
         FROM announcements a
         JOIN doctors d ON d.id = a.doctor_id
         WHERE a.course_id = $courseId 
         ORDER BY a.created_at DESC"
    );
}

public function codeExists(string $code, int $excludeId = 0): bool {
    $r = $this->queryOne(
        "SELECT 1 FROM courses WHERE code = '$code' AND id != $excludeId"
    );
    return (bool)$r;
}
}
