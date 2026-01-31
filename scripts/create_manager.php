<?php
// Script to create the initial manager account
require_once __DIR__ . '/../app/config/config.php';

// Autoloader copy (since this is a standalone script)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

use App\Core\DB;

$username = 'manager';
$password = 'change_me';
$fullName = 'Admin Manager';

$db = DB::getInstance();

// Check if exists
$stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    die("Manager already exists.\n");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'manager')");
if ($stmt->execute([$username, $hashedPassword, $fullName])) {
    echo "Manager account created successfully.\n";
    echo "Username: $username\n";
    echo "Password: $password\n";
} else {
    echo "Error creating manager account.\n";
}
