<?php
namespace App\Middleware;

use App\Core\Auth;

class RequireRole {
    private $allowedRoles;

    public function __construct($roles = []) {
        $this->allowedRoles = $roles;
    }

    public function handle() {
        if (!Auth::check() || !in_array(Auth::role(), $this->allowedRoles)) {
            http_response_code(403);
            die("Forbidden: You do not have permission to access this page.");
        }
        return true;
    }
}
