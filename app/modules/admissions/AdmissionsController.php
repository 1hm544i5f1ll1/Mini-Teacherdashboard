<?php
namespace App\Modules\Admissions;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Logger;

class AdmissionsController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new AdmissionsRepo();
    }

    public function index() {
        $admissions = $this->repo->getAllPending();
        $this->view('teacher/admissions', ['admissions' => $admissions]);
    }

    public function submitResult() {
        // Validation for committee name allowlist
        $allowedCommittees = ['Medical', 'Educational', 'Behavioral', 'SocialWorker', 'SpeechTherapist', 'Parent', 'IT', 'Activities', 'PE', 'Music'];
        if (!in_array($_POST['committee_name'], $allowedCommittees)) {
            die("Invalid committee");
        }

        try {
            $this->repo->addCommitteeResult($_POST);
            Logger::audit("Submitted committee result", "admission", $_POST['admission_id'], "Committee: " . $_POST['committee_name']);
            $this->redirect('/teacher/admissions?success=submitted');
        } catch (\Exception $e) {
            $this->redirect('/teacher/admissions?error=db');
        }
    }

    public function finalDecision() {
        if (!Auth::isManager()) die("Unauthorized");

        $id = $_POST['admission_id'];
        $decision = $_POST['decision']; // accepted/rejected
        $note = $_POST['note'] ?? '';

        try {
            $this->repo->updateStatus($id, $decision, $note);
            Logger::audit("Final admission decision", "admission", $id, "Decision: $decision");
            $this->redirect('/manager/admissions?success=decision_made');
        } catch (\Exception $e) {
            $this->redirect('/manager/admissions?error=db');
        }
    }
}
