<?php
// Database configuration
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3307);   // or 3306 (match XAMPP my.ini)
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sohag_kg_system');


// App configuration
define('APP_NAME', 'Sohag School KG System');

// Auto-detect APP_URL based on current request
if (!defined('APP_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    // For PHP built-in server, remove the /public part if present
    if (strpos($scriptName, '/public') !== false) {
        $scriptName = str_replace('/public', '', $scriptName);
    }
    // If script is in root, use empty string
    if ($scriptName === '/' || $scriptName === '\\') {
        $scriptName = '';
    }
    define('APP_URL', $protocol . '://' . $host . $scriptName);
}

define('BASE_PATH', dirname(__DIR__, 2));

// Security
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');

// Folders
define('STORAGE_PATH', BASE_PATH . '/storage');
define('UPLOAD_PATH', STORAGE_PATH . '/uploads');
define('LOG_PATH', STORAGE_PATH . '/logs');
