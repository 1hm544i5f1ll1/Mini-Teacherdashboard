<?php
namespace App\Modules\Registration;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Logger;
use App\Core\Validator;
use App\Core\Csrf;

class RegistrationController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new RegistrationRepo();
    }

    /** List (registrar: draft/submitted; manager: all) */
    public function index() {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'grade' => $_GET['grade'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
            'search' => trim($_GET['search'] ?? ''),
        ];
        if (Auth::isTeacher()) {
            $filters['status'] = $filters['status'] ?: null;
        }
        $list = $this->repo->listRegistrations($filters);
        $this->view('registration/index', ['registrations' => $list, 'filters' => $filters]);
    }

    /** Create form */
    public function createForm() {
        $this->view('registration/form', ['registration' => null]);
    }

    /** Store new registration (draft) */
    public function store() {
        if (!Auth::isTeacher() && !Auth::isManager()) {
            $this->redirect('/registration?error=forbidden');
            return;
        }
        $rules = [
            'full_name' => 'required|min:2',
            'gender' => 'required|enum:male,female',
            'dob' => 'required',
            'applied_grade' => 'required|enum:PRE_KG,KG1',
            'guardian_name' => 'required',
            'guardian_phone' => 'required',
        ];
        $v = new Validator();
        if (!$v->validate($_POST, $rules)) {
            $_SESSION['form_errors'] = $v->getErrors();
            $_SESSION['form_old'] = $_POST;
            $this->redirect('/registration/create');
            return;
        }
        try {
            $id = $this->repo->createRegistration($_POST, Auth::id());
            Logger::audit('Registration created', 'admission', $id, 'Draft');
            $this->redirect('/registration?success=created');
        } catch (\Throwable $e) {
            Logger::log('Registration create error: ' . $e->getMessage(), 'app');
            $this->redirect('/registration/create?error=db');
        }
    }

    /** Edit form (only draft, or manager view) */
    public function editForm() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        $reg = $this->repo->getRegistration($id);
        if (!$reg) {
            $this->redirect('/registration?error=notfound');
            return;
        }
        $canEdit = ($reg['status'] === 'draft' && (Auth::isTeacher() || Auth::isManager())) || (Auth::isManager() && empty($reg['locked_at']));
        $committeesConfig = require BASE_PATH . '/app/config/committees.php';
        $committeeResults = $this->repo->getCommitteeResults($id);
        $this->view('registration/form', ['registration' => $reg, 'can_edit' => $canEdit, 'committees_config' => $committeesConfig, 'committee_results' => $committeeResults]);
    }

    /** Update (only when draft) */
    public function update() {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        $reg = $this->repo->getRegistration($id);
        if (!$reg || $reg['status'] !== 'draft') {
            $this->redirect('/registration?error=notfound');
            return;
        }
        $rules = [
            'full_name' => 'required|min:2',
            'gender' => 'required|enum:male,female',
            'dob' => 'required',
            'applied_grade' => 'required|enum:PRE_KG,KG1',
            'guardian_name' => 'required',
            'guardian_phone' => 'required',
        ];
        $v = new Validator();
        if (!$v->validate($_POST, $rules)) {
            $_SESSION['form_errors'] = $v->getErrors();
            $_SESSION['form_old'] = $_POST;
            $this->redirect("/registration/edit?id=$id");
            return;
        }
        try {
            $this->repo->updateRegistration($id, $_POST, Auth::id());
            if (!empty($_POST['committee']) && is_array($_POST['committee'])) {
                $this->repo->saveAllCommittees($id, $_POST['committee']);
            }
            Logger::audit('Registration updated', 'admission', $id, 'Draft');
            $this->redirect('/registration?success=updated');
        } catch (\Throwable $e) {
            Logger::log('Registration update error: ' . $e->getMessage(), 'app');
            $this->redirect("/registration/edit?id=$id&error=db");
        }
    }

    /** Submit (registrar) */
    public function submit() {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        if (!Auth::isTeacher() && !Auth::isManager()) {
            $this->redirect('/registration?error=forbidden');
            return;
        }
        if ($this->repo->submit($id, Auth::id())) {
            Logger::audit('Registration submitted', 'admission', $id, 'Submitted');
            $this->redirect('/registration?success=submitted');
        } else {
            $this->redirect('/registration?error=submit');
        }
    }

    /** Manager: approve */
    public function approve() {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        if (!Auth::isManager()) {
            $this->redirect('/registration?error=forbidden');
            return;
        }
        $note = $_POST['note'] ?? '';
        if ($this->repo->approve($id, $note, Auth::id())) {
            Logger::audit('Registration approved', 'admission', $id, $note);
            $this->redirect('/registration?success=approved');
        } else {
            $this->redirect('/registration?error=approve');
        }
    }

    /** Manager: reject */
    public function reject() {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        if (!Auth::isManager()) {
            $this->redirect('/registration?error=forbidden');
            return;
        }
        $note = $_POST['note'] ?? '';
        if ($this->repo->reject($id, $note, Auth::id())) {
            Logger::audit('Registration rejected', 'admission', $id, $note);
            $this->redirect('/registration?success=rejected');
        } else {
            $this->redirect('/registration?error=reject');
        }
    }

    /** Manager: lock */
    public function lock() {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        if (!Auth::isManager()) {
            $this->redirect('/registration?error=forbidden');
            return;
        }
        if ($this->repo->lock($id, Auth::id())) {
            Logger::audit('Registration locked', 'admission', $id, 'Locked');
            $this->redirect('/registration?success=locked');
        } else {
            $this->redirect('/registration?error=lock');
        }
    }

    /** Registration summary (view / print PDF) */
    public function summary() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { $this->redirect('/registration?error=id'); return; }
        $reg = $this->repo->getRegistration($id);
        if (!$reg) {
            $this->redirect('/registration?error=notfound');
            return;
        }
        $documents = $this->repo->getDocuments($reg['student_id']);
        $committeesConfig = require BASE_PATH . '/app/config/committees.php';
        $committeeResults = $this->repo->getCommitteeResults($id);
        $this->view('registration/summary', ['registration' => $reg, 'documents' => $documents, 'committees_config' => $committeesConfig, 'committee_results' => $committeeResults]);
    }
}
