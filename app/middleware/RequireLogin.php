<?php
namespace App\Middleware;

use App\Core\Auth;
use App\Core\View;

class RequireLogin {
    public function handle() {
        if (!Auth::check()) {
            header("Location: " . APP_URL . "/auth/login");
            exit();
            return false;
        }
        return true;
    }
}
