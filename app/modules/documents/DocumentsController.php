<?php
namespace App\Modules\Documents;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Logger;

class DocumentsController extends Controller {
    private static $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
    private static $allowedExt = ['pdf', 'jpg', 'jpeg', 'png'];
    private static $maxSize = 5 * 1024 * 1024; // 5MB
    private static $docTypes = ['birth_certificate', 'photo', 'committee_scan', 'other'];

    /** Upload document for a registration (student_id from admission) */
    public function upload() {
        if (!Auth::check()) {
            http_response_code(403);
            exit('Forbidden');
        }
        $studentId = (int)($_POST['student_id'] ?? 0);
        $admissionId = (int)($_POST['admission_id'] ?? 0);
        $docType = $_POST['document_type'] ?? 'other';
        if (!in_array($docType, self::$docTypes)) $docType = 'other';

        if (!$studentId || !$admissionId) {
            $this->redirect('/registration?error=upload_id');
            return;
        }

        // Verify user can access this registration
        $reg = (new \App\Modules\Registration\RegistrationRepo())->getRegistration($admissionId);
        if (!$reg || (int)$reg['student_id'] !== $studentId) {
            $this->redirect('/registration?error=forbidden');
            return;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect("/registration/edit?id=$admissionId&error=upload");
            return;
        }

        $file = $_FILES['file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowedExt)) {
            $this->redirect("/registration/edit?id=$admissionId&error=type");
            return;
        }
        if ($file['size'] > self::$maxSize) {
            $this->redirect("/registration/edit?id=$admissionId&error=size");
            return;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']) ?: $file['type'];
        finfo_close($finfo);
        if (!in_array($mime, self::$allowedTypes)) {
            $this->redirect("/registration/edit?id=$admissionId&error=type");
            return;
        }

        $uploadDir = STORAGE_PATH . '/uploads/' . $studentId;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
        $baseName = date('Ymd_His') . '_' . $safeName;
        $relPath = 'uploads/' . $studentId . '/' . $baseName;
        $fullPath = STORAGE_PATH . '/' . $relPath;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->redirect("/registration/edit?id=$admissionId&error=save");
            return;
        }

        $db = DB::getInstance();
        $stmt = $db->prepare("INSERT INTO student_documents (student_id, file_name, file_path, file_type, document_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$studentId, $file['name'], $relPath, $mime, $docType]);

        Logger::audit('Document uploaded', 'student_document', $db->lastInsertId(), "student_id=$studentId type=$docType");
        $this->redirect("/registration/edit?id=$admissionId&success=upload");
    }

    /** Safe download: only if user can see this registration */
    public function download() {
        if (!Auth::check()) {
            http_response_code(403);
            exit('Forbidden');
        }
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            http_response_code(404);
            exit('Not found');
        }

        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT sd.*, a.id as admission_id FROM student_documents sd JOIN students s ON sd.student_id = s.id JOIN admissions a ON a.student_id = s.id WHERE sd.id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            http_response_code(404);
            exit('Not found');
        }

        $fullPath = STORAGE_PATH . '/' . $row['file_path'];
        if (!is_file($fullPath)) {
            http_response_code(404);
            exit('File not found');
        }

        header('Content-Type: ' . $row['file_type']);
        header('Content-Disposition: attachment; filename="' . basename($row['file_name']) . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }
}
