<?php
namespace App\Modules\Students;

use App\Core\DB;
use PDO;

class StudentsRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getAll($filters = []) {
        $sql = "SELECT s.*, g.name as guardian_name, g.phone as guardian_phone, c.name as class_name 
                FROM students s 
                JOIN guardians g ON s.guardian_id = g.id 
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $sql .= " AND s.full_name LIKE ?";
            $params[] = "%" . $filters['name'] . "%";
        }

        if (!empty($filters['class_id'])) {
            $sql .= " AND s.class_id = ?";
            $params[] = $filters['class_id'];
        }

        return $this->db->prepare($sql)->execute($params)->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT s.*, g.name as guardian_name, g.phone as guardian_phone, g.national_id as guardian_national_id, g.relationship as guardian_relationship
                                   FROM students s 
                                   JOIN guardians g ON s.guardian_id = g.id 
                                   WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $this->db->beginTransaction();
        try {
            // Create guardian first
            $stmt = $this->db->prepare("INSERT INTO guardians (name, phone, national_id, relationship) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['guardian_name'],
                $data['guardian_phone'],
                $data['guardian_national_id'],
                $data['guardian_relationship']
            ]);
            $guardianId = $this->db->lastInsertId();

            // Create student
            $stmt = $this->db->prepare("INSERT INTO students (full_name, gender, dob, birth_place, religion, language, second_language, grade, class_id, academic_year, address, guardian_id) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['full_name'],
                $data['gender'],
                $data['dob'],
                $data['birth_place'],
                $data['religion'],
                $data['language'],
                $data['second_language'],
                $data['grade'],
                $data['class_id'] ?? null,
                $data['academic_year'],
                $data['address'],
                $guardianId
            ]);
            $studentId = $this->db->lastInsertId();

            $this->db->commit();
            return $studentId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
