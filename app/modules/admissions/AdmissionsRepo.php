<?php
namespace App\Modules\Admissions;

use App\Core\DB;
use PDO;

class AdmissionsRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getAllPending() {
        $stmt = $this->db->prepare("SELECT a.*, s.full_name, s.grade 
                                   FROM admissions a 
                                   JOIN students s ON a.student_id = s.id 
                                   WHERE a.status IN ('pending', 'testing')");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addCommitteeResult($data) {
        $stmt = $this->db->prepare("INSERT INTO committee_results (admission_id, committee_name, score, result, examiner, notes) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['admission_id'],
            $data['committee_name'],
            $data['score'],
            $data['result'],
            $data['examiner'],
            $data['notes']
        ]);
        return $this->db->lastInsertId();
    }

    public function updateStatus($admissionId, $status, $note = null) {
        $stmt = $this->db->prepare("UPDATE admissions SET status = ?, decision_note = ?, decision_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $note, $admissionId]);
    }
}
