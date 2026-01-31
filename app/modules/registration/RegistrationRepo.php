<?php
namespace App\Modules\Registration;

use App\Core\DB;

class RegistrationRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    /** List registrations with filters (status, grade, date) and search (name, phone, ID) */
    public function listRegistrations(array $filters = []) {
        $sql = "SELECT a.id, a.status, a.applied_grade, a.academic_year, a.created_at, a.submitted_at, a.decision_note, a.locked_at,
                       s.id as student_id, s.full_name, s.gender, s.dob, s.religion,
                       g.name as guardian_name, g.phone as guardian_phone, g.phone_2 as guardian_phone_2
                FROM admissions a
                JOIN students s ON a.student_id = s.id
                JOIN guardians g ON s.guardian_id = g.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['grade'])) {
            $sql .= " AND a.applied_grade = ?";
            $params[] = $filters['grade'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(a.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(a.created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['search'])) {
            $q = '%' . $filters['search'] . '%';
            $sid = is_numeric($filters['search']) ? (int)$filters['search'] : -1;
            $sql .= " AND (s.full_name LIKE ? OR g.phone LIKE ? OR g.phone_2 LIKE ? OR s.id = ? OR a.id = ?)";
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
            $params[] = $sid;
            $params[] = $sid;
        }

        $sql .= " ORDER BY a.created_at DESC";
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . (int)$filters['limit'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Get one registration by admission id (for view/edit) */
    public function getRegistration($admissionId) {
        $stmt = $this->db->prepare("SELECT a.*, s.id as student_id, s.full_name, s.gender, s.dob, s.birth_place, s.religion, s.address as student_address,
                                          g.id as guardian_id, g.name as guardian_name, g.phone as guardian_phone, g.phone_2 as guardian_phone_2, g.national_id as guardian_national_id, g.relationship, g.address as guardian_address
                                   FROM admissions a
                                   JOIN students s ON a.student_id = s.id
                                   JOIN guardians g ON s.guardian_id = g.id
                                   WHERE a.id = ?");
        $stmt->execute([$admissionId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /** Create registration: guardian + student + admission (draft) */
    public function createRegistration(array $data, $createdBy) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO guardians (name, phone, phone_2, national_id, relationship, address) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['guardian_name'] ?? '',
                $data['guardian_phone'] ?? '',
                $data['guardian_phone_2'] ?? null,
                $data['guardian_national_id'] ?? null,
                $data['guardian_relationship'] ?? null,
                $data['guardian_address'] ?? null
            ]);
            $guardianId = $this->db->lastInsertId();

            $stmt = $this->db->prepare("INSERT INTO students (full_name, gender, dob, birth_place, religion, language, second_language, grade, class_id, academic_year, address, guardian_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['full_name'] ?? '',
                $data['gender'] ?? 'male',
                $data['dob'] ?? null,
                $data['birth_place'] ?? null,
                $data['religion'] ?? null,
                null,
                null,
                $data['applied_grade'] ?? 'KG1',
                null,
                $data['academic_year'] ?? '2025-2026',
                $data['address'] ?? null,
                $guardianId
            ]);
            $studentId = $this->db->lastInsertId();

            $stmt = $this->db->prepare("INSERT INTO admissions (student_id, applied_grade, academic_year, status, created_by) VALUES (?, ?, ?, 'draft', ?)");
            $stmt->execute([
                $studentId,
                $data['applied_grade'] ?? 'KG1',
                $data['academic_year'] ?? '2025-2026',
                $createdBy
            ]);
            $admissionId = $this->db->lastInsertId();

            $this->db->commit();
            return $admissionId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Update registration (student + guardian) — only when status = draft */
    public function updateRegistration($admissionId, array $data, $updatedBy) {
        $reg = $this->getRegistration($admissionId);
        if (!$reg || $reg['status'] !== 'draft') {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->db->prepare("UPDATE guardians SET name=?, phone=?, phone_2=?, national_id=?, relationship=?, address=? WHERE id=?")
                ->execute([
                    $data['guardian_name'] ?? $reg['guardian_name'],
                    $data['guardian_phone'] ?? $reg['guardian_phone'],
                    $data['guardian_phone_2'] ?? $reg['guardian_phone_2'],
                    $data['guardian_national_id'] ?? $reg['guardian_national_id'],
                    $data['guardian_relationship'] ?? $reg['guardian_relationship'],
                    $data['guardian_address'] ?? $reg['guardian_address'],
                    $reg['guardian_id']
                ]);

            $this->db->prepare("UPDATE students SET full_name=?, gender=?, dob=?, birth_place=?, religion=?, grade=?, address=? WHERE id=?")
                ->execute([
                    $data['full_name'] ?? $reg['full_name'],
                    $data['gender'] ?? $reg['gender'],
                    $data['dob'] ?? $reg['dob'],
                    $data['birth_place'] ?? $reg['birth_place'],
                    $data['religion'] ?? $reg['religion'],
                    $data['applied_grade'] ?? $reg['applied_grade'],
                    $data['address'] ?? $reg['student_address'],
                    $reg['student_id']
                ]);

            $this->db->prepare("UPDATE admissions SET updated_by=?, applied_grade=?, academic_year=? WHERE id=?")
                ->execute([
                    $updatedBy,
                    $data['applied_grade'] ?? $reg['applied_grade'],
                    $data['academic_year'] ?? $reg['academic_year'],
                    $admissionId
                ]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Submit: freeze edits for registrar */
    public function submit($admissionId, $userId) {
        $reg = $this->getRegistration($admissionId);
        if (!$reg || $reg['status'] !== 'draft') return false;
        $stmt = $this->db->prepare("UPDATE admissions SET status='submitted', submitted_at=NOW(), updated_by=? WHERE id=?");
        $stmt->execute([$userId, $admissionId]);
        return true;
    }

    /** Manager approve */
    public function approve($admissionId, $note, $userId) {
        $reg = $this->getRegistration($admissionId);
        if (!$reg || !in_array($reg['status'], ['submitted'], true)) return false;
        $stmt = $this->db->prepare("UPDATE admissions SET status='approved', decision_note=?, decision_at=NOW(), approved_by=? WHERE id=?");
        $stmt->execute([$note, $userId, $admissionId]);
        return true;
    }

    /** Manager reject */
    public function reject($admissionId, $note, $userId) {
        $reg = $this->getRegistration($admissionId);
        if (!$reg || !in_array($reg['status'], ['submitted'], true)) return false;
        $stmt = $this->db->prepare("UPDATE admissions SET status='rejected', decision_note=?, decision_at=NOW(), approved_by=? WHERE id=?");
        $stmt->execute([$note, $userId, $admissionId]);
        return true;
    }

    /** Manager lock (no more edits) */
    public function lock($admissionId, $userId) {
        $reg = $this->getRegistration($admissionId);
        if (!$reg || !in_array($reg['status'], ['approved', 'rejected'], true)) return false;
        $stmt = $this->db->prepare("UPDATE admissions SET locked_by=?, locked_at=NOW() WHERE id=?");
        $stmt->execute([$userId, $admissionId]);
        return true;
    }

    /** Check if registration is locked */
    public function isLocked($admissionId) {
        $stmt = $this->db->prepare("SELECT locked_at FROM admissions WHERE id = ?");
        $stmt->execute([$admissionId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row && !empty($row['locked_at']);
    }

    /** Get documents for a registration (by student_id) */
    public function getDocuments($studentId) {
        $stmt = $this->db->prepare("SELECT id, file_name, file_type, document_type, uploaded_at FROM student_documents WHERE student_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
