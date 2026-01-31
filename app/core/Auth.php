<?php
namespace App\Core;

class Auth {
    public static function login($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['last_activity'] = time();
    }

    public static function logout() {
        $_SESSION = [];
        session_destroy();
    }

    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        if (time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function user() {
        return $_SESSION;
    }

    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function role() {
        return $_SESSION['role'] ?? null;
    }

    public static function isManager() {
        return self::role() === 'manager';
    }

    public static function isTeacher() {
        return self::role() === 'teacher';
    }
}
