<?php
namespace App\Modules\HR;

use App\Core\DB;

class HRRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getAllEmployees() {
        return $this->db->query("SELECT * FROM employees")->fetchAll();
    }

    public function getPendingLeaves() {
        return $this->db->query("SELECT l.*, e.full_name FROM leave_requests l JOIN employees e ON l.employee_id = e.id WHERE l.status = 'pending'")->fetchAll();
    }

    public function updateLeaveStatus($id, $status, $managerId) {
        $stmt = $this->db->prepare("UPDATE leave_requests SET status = ?, approved_by = ? WHERE id = ?");
        $stmt->execute([$status, $managerId, $id]);
    }
}
