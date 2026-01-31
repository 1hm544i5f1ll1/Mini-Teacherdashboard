<?php
namespace App\Core;

class Logger {
    public static function log($message, $type = 'app') {
        $file = LOG_PATH . '/' . $type . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userId = Auth::id() ?? 'guest';
        $logEntry = "[$timestamp] [User ID: $userId] [IP: $ip] $message" . PHP_EOL;
        
        file_put_contents($file, $logEntry, FILE_APPEND);
    }

    public static function audit($action, $targetType = null, $targetId = null, $details = null) {
        $db = DB::getInstance();
        $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, target_type, target_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            Auth::id(),
            $action,
            $targetType,
            $targetId,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        self::log("Audit: $action ($targetType ID: $targetId) - Details: $details", 'audit');
    }

    public static function security($message) {
        self::log("Security Alert: $message", 'security');
    }
}
