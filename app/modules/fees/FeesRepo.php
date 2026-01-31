<?php
namespace App\Modules\Fees;

use App\Core\DB;

class FeesRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getStudentInvoices($studentId) {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function createPayment($data) {
        $stmt = $this->db->prepare("INSERT INTO payments (invoice_id, amount, method, date, receipt_ref, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['invoice_id'],
            $data['amount'],
            $data['method'],
            $data['date'],
            $data['receipt_ref'],
            $data['notes']
        ]);
        
        // Update invoice status if fully paid
        // (Simplified logic for now)
    }
}
