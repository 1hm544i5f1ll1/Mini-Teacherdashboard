<?php
namespace App\Modules\Attendance;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Logger;

class AttendanceController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new AttendanceRepo();
    }

    public function showTake() {
        $classes = $this->repo->getTeacherClasses(Auth::id());
        $this->view('teacher/attendance', ['classes' => $classes]);
    }

    public function loadStudents() {
        $classId = $_GET['class_id'] ?? null;
        if (!$classId) $this->json(['error' => 'No class ID']);

        // Verify teacher belongs to this class
        $classes = $this->repo->getTeacherClasses(Auth::id());
        $classIds = array_column($classes, 'id');
        if (!in_array($classId, $classIds)) {
            $this->json(['error' => 'Unauthorized']);
        }

        $students = $this->repo->getStudentsByClass($classId);
        $this->json(['students' => $students]);
    }

    public function store() {
        $classId = $_POST['class_id'] ?? null;
        $date = $_POST['date'] ?? date('Y-m-d');
        $attendance = $_POST['attendance'] ?? []; // Array of student_id => status

        if (!$classId) $this->redirect('/teacher/attendance?error=missing_data');

        $records = [];
        foreach ($attendance as $studentId => $status) {
            $records[] = [
                'student_id' => $studentId,
                'status' => $status,
                'reason' => $_POST['reason'][$studentId] ?? ''
            ];
        }

        try {
            $this->repo->saveSession($classId, $date, $records);
            Logger::audit("Taken attendance", "class", $classId, "Date: $date");
            $this->redirect('/teacher/attendance?success=saved');
        } catch (\Exception $e) {
            $this->redirect('/teacher/attendance?error=db');
        }
    }
}
