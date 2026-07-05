<?php
require_once __DIR__ . '/../../core/Model.php';

class StudentQuestion extends Model {
    protected string $table = 'student_questions';

public function getForCourse(int $courseId): array {
    return $this->query(
        "SELECT sq.*, s.name AS student_name, s.profile_image AS student_image, d.name AS doctor_name
         FROM student_questions sq
         JOIN students s ON s.id = sq.student_id
         LEFT JOIN doctors d ON d.id = sq.answered_by
         WHERE sq.course_id = $courseId
         ORDER BY sq.created_at DESC"
    );
}

public function getForStudent(int $studentId): array {
    return $this->query(
        "SELECT sq.*, c.name AS course_name, d.name AS doctor_name
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         LEFT JOIN doctors d ON d.id = sq.answered_by
         WHERE sq.student_id = $studentId
         ORDER BY sq.created_at DESC"
    );
}

public function getForDoctor(int $doctorId): array {
    return $this->query(
        "SELECT sq.*, s.name AS student_name, s.profile_image AS student_image, c.name AS course_name
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         JOIN students s ON s.id = sq.student_id
         WHERE c.doctor_id = $doctorId
         ORDER BY sq.status ASC, sq.created_at DESC"
    );
}

public function answer(int $questionId, int $doctorId, string $answerText): bool {
    return $this->execute(
        "UPDATE student_questions 
         SET answer_text = '$answerText', answered_by = $doctorId, answered_at = NOW(), status = 'answered' 
         WHERE id = $questionId"
    );
}

public function getWithDetails(int $id): array|false {
    return $this->queryOne(
        "SELECT sq.*, s.name AS student_name, c.name AS course_name, c.doctor_id
         FROM student_questions sq
         JOIN students s ON s.id = sq.student_id
         JOIN courses c ON c.id = sq.course_id
         WHERE sq.id = $id"
    );
}

public function getNewAnsweredForStudent(int $studentId, string $since): array {
    return $this->query(
        "SELECT sq.*, c.name AS course_name, d.name AS doctor_name
         FROM student_questions sq
         JOIN courses c ON c.id = sq.course_id
         JOIN doctors d ON d.id = sq.answered_by
         WHERE sq.student_id = $studentId 
           AND sq.status = 'answered' 
           AND sq.answered_at > '$since'
         ORDER BY sq.answered_at DESC"
    );
}
}
