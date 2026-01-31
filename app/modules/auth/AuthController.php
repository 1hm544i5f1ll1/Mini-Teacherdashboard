<?php
namespace App\Modules\Auth;

use App\Core\Controller;
use App\Core\DB;
use App\Core\Auth;
use App\Core\Logger;
use App\Core\View;

class AuthController extends Controller {
    public function showLogin() {
        if (Auth::check()) {
            $this->redirect(Auth::isManager() ? '/manager' : '/teacher');
        }
        $this->view('auth/login');
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND status = 'active'");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Log attempt
            $this->logAttempt($username, true);
            
            Auth::login($user);
            Logger::log("User logged in: $username");
            
            $this->redirect('/registration');
        } else {
            $this->logAttempt($username, false);
            Logger::security("Failed login attempt for username: $username");
            
            $this->view('auth/login', ['error' => 'Invalid credentials or inactive account.']);
        }
    }

    public function logout() {
        Logger::log("User logged out: " . $_SESSION['username']);
        Auth::logout();
        $this->redirect('/auth/login');
    }

    private function logAttempt($username, $success) {
        $db = DB::getInstance();
        $stmt = $db->prepare("INSERT INTO login_attempts (username, ip_address, is_successful) VALUES (?, ?, ?)");
        $stmt->execute([$username, $_SERVER['REMOTE_ADDR'], $success]);
    }
}
