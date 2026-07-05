<?php
require_once __DIR__ . '/../../core/Model.php';

class Doctor extends Model {
    protected string $table = 'doctors';

public function findByEmail(string $email): array|false {
    return $this->findOne("email = '$email'");
}

public function findByNationalId(string $nid): array|false {
    return $this->findOne("national_id = '$nid'");
}

public function getCourses(int $doctorId): array {
    return $this->query(
        "SELECT c.*, 
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) AS student_count,
                (SELECT COUNT(*) FROM contents WHERE course_id = c.id) AS content_count,
                (SELECT COUNT(*) FROM student_questions WHERE course_id = c.id AND status = 'pending') AS pending_questions
         FROM courses c
         WHERE c.doctor_id = $doctorId
         ORDER BY c.created_at DESC"
    );
}

public function getPendingQuestionsCount(int $doctorId): int {
    $r = $this->queryOne(
        "SELECT COUNT(*) as cnt 
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         WHERE c.doctor_id = $doctorId AND sq.status = 'pending'"
    );
    return (int)($r['cnt'] ?? 0);
}

public function getNewQuestionsCount(int $doctorId): int {
    $r = $this->queryOne(
        "SELECT COUNT(*) as cnt 
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         WHERE c.doctor_id = $doctorId AND sq.status = 'new'"
    );
    return (int)($r['cnt'] ?? 0);
}
}
