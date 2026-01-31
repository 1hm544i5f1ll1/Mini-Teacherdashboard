<?php
namespace App\Modules\Exams;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Logger;

class ExamsController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new ExamsRepo();
    }

    public function index() {
        // Implementation for listing exams
        $this->view('teacher/exams');
    }

    public function storeResults() {
        $examId = $_POST['exam_id'];
        $scores = $_POST['scores']; // student_id => score
        
        $results = [];
        foreach ($scores as $studentId => $score) {
            $results[] = [
                'student_id' => $studentId,
                'score' => $score,
                'comment' => $_POST['comments'][$studentId] ?? '',
                'entered_by' => Auth::id()
            ];
        }

        try {
            $this->repo->saveResults($examId, $results);
            Logger::audit("Entered exam marks", "exam", $examId);
            $this->redirect('/teacher/exams?success=marks_saved');
        } catch (\Exception $e) {
            $this->redirect('/teacher/exams?error=db');
        }
    }
}
