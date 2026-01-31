<?php
namespace App\Modules\Students;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\Logger;
use App\Core\Auth;

class StudentsController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new StudentsRepo();
    }

    public function index() {
        $filters = [
            'name' => $_GET['name'] ?? null,
            'class_id' => $_GET['class_id'] ?? null
        ];
        
        // If teacher, restrict to their classes
        if (Auth::isTeacher()) {
            // Logic to get teacher classes and filter repo call
        }

        $students = $this->repo->getAll($filters);
        $this->view('teacher/students', ['students' => $students]);
    }

    public function store() {
        $validator = new Validator();
        $rules = [
            'full_name' => 'required|min:3',
            'gender' => 'required|enum:male,female',
            'dob' => 'required',
            'grade' => 'required|enum:PRE_KG,KG1',
            'guardian_name' => 'required',
            'guardian_phone' => 'required',
            'guardian_national_id' => 'required'
        ];

        if (!$validator->validate($_POST, $rules)) {
            // Handle validation errors
            return $this->redirect('/teacher/students?error=validation');
        }

        try {
            $studentId = $this->repo->create($_POST);
            Logger::audit("Created student", "student", $studentId, "Name: " . $_POST['full_name']);
            $this->redirect('/teacher/students?success=created');
        } catch (\Exception $e) {
            Logger::log("Error creating student: " . $e->getMessage(), 'error');
            $this->redirect('/teacher/students?error=db');
        }
    }
}
