<?php
require_once __DIR__ . '/../../core/Model.php';

class Content extends Model {
    protected string $table = 'contents';

public function getForCourse(int $courseId): array {
    return $this->query(
        "SELECT c.*, 
                (SELECT COUNT(*) FROM content_views WHERE content_id = c.id) AS view_count
         FROM contents c 
         WHERE c.course_id = $courseId 
         ORDER BY c.order_num ASC, c.created_at ASC"
    );
}

public function getWithCourse(int $id): array|false {
    return $this->queryOne(
        "SELECT ct.*, c.doctor_id, c.name AS course_name, c.status AS course_status
         FROM contents ct 
         JOIN courses c ON c.id = ct.course_id 
         WHERE ct.id = $id"
    );
}

public function markViewed(int $contentId, int $studentId): void {
    $this->execute("INSERT IGNORE INTO content_views (content_id, student_id) VALUES ($contentId, $studentId)");
}

public function isViewed(int $contentId, int $studentId): bool {
    return (bool)$this->queryOne(
        "SELECT 1 FROM content_views WHERE content_id = $contentId AND student_id = $studentId"
    );
}

public function getNextOrder(int $courseId): int {
    $r = $this->queryOne("SELECT MAX(order_num) as max_order FROM contents WHERE course_id = $courseId");
    return (int)($r['max_order'] ?? 0) + 1;
}
}
