<?php
namespace App\Modules\Exams;

use App\Core\DB;
use PDO;

class ExamsRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getExamsByClass($classId) {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE class_id = ? ORDER BY date DESC");
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public function saveResults($examId, $results) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO exam_results (exam_id, student_id, score, comment, entered_by) 
                                       VALUES (?, ?, ?, ?, ?)
                                       ON DUPLICATE KEY UPDATE score = VALUES(score), comment = VALUES(comment), entered_by = VALUES(entered_by)");
            foreach ($results as $res) {
                $stmt->execute([$examId, $res['student_id'], $res['score'], $res['comment'] ?? '', $res['entered_by']]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
