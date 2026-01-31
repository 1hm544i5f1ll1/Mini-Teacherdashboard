<?php
namespace App\Modules\Attendance;

use App\Core\DB;
use App\Core\Auth;
use PDO;

class AttendanceRepo {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function getTeacherClasses($teacherId) {
        $stmt = $this->db->prepare("SELECT c.* FROM classes c 
                                   JOIN teacher_classes tc ON c.id = tc.class_id 
                                   WHERE tc.user_id = ? AND c.status = 'active'");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }

    public function getStudentsByClass($classId) {
        $stmt = $this->db->prepare("SELECT id, full_name, status FROM students WHERE class_id = ? AND status = 'active'");
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public function saveSession($classId, $date, $records) {
        $this->db->beginTransaction();
        try {
            // Check if session exists for this day/class
            $stmt = $this->db->prepare("SELECT id FROM attendance_sessions WHERE class_id = ? AND date = ?");
            $stmt->execute([$classId, $date]);
            $sessionId = $stmt->fetchColumn();

            if (!$sessionId) {
                $stmt = $this->db->prepare("INSERT INTO attendance_sessions (class_id, date, taken_by) VALUES (?, ?, ?)");
                $stmt->execute([$classId, $date, Auth::id()]);
                $sessionId = $this->db->lastInsertId();
            } else {
                // Clear old records if re-taking
                $stmt = $this->db->prepare("DELETE FROM attendance_records WHERE session_id = ?");
                $stmt->execute([$sessionId]);
            }

            $stmt = $this->db->prepare("INSERT INTO attendance_records (session_id, student_id, status, reason) VALUES (?, ?, ?, ?)");
            foreach ($records as $record) {
                $stmt->execute([$sessionId, $record['student_id'], $record['status'], $record['reason'] ?? '']);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
