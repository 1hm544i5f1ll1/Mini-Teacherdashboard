<?php
/**
 * Run registration MVP migration using PHP (no mysql CLI needed).
 * Run from project root: php scripts/run_migration.php
 */

$configPath = __DIR__ . '/../app/config/config.php';
if (!file_exists($configPath)) {
    echo "Config not found.\n";
    exit(1);
}
require $configPath;

$migrationFile = __DIR__ . '/../database/migrations/001_registration_mvp.sql';
if (!file_exists($migrationFile)) {
    echo "Migration file not found.\n";
    exit(1);
}

$port = defined('DB_PORT') ? DB_PORT : 3306;
$dsn = "mysql:host=" . DB_HOST . ";port=$port;dbname=" . DB_NAME . ";charset=utf8mb4";

echo "Connecting to " . DB_NAME . " ...\n";

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

$sql = file_get_contents($migrationFile);
// Remove single-line comments, drop USE statement
$sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
$sql = preg_replace('/^\s*USE\s+\w+\s*;/mi', '', $sql);
// Split by semicolon (statements may span multiple lines)
$parts = explode(';', $sql);
$statements = [];
foreach ($parts as $p) {
    $p = trim($p);
    if ($p === '') continue;
    $statements[] = $p . ';';
}

$done = 0;
$skipped = 0;
foreach ($statements as $stmt) {
    if ($stmt === '') continue;
    try {
        $pdo->exec($stmt);
        $done++;
        echo ".";
    } catch (PDOException $e) {
        // Duplicate column / key = already applied, skip
        if (strpos($e->getMessage(), 'Duplicate column') !== false || strpos($e->getMessage(), 'Duplicate key') !== false) {
            $skipped++;
            echo "s";
        } else {
            echo "\nError: " . $e->getMessage() . "\n";
            echo "Statement: " . substr($stmt, 0, 80) . "...\n";
            exit(1);
        }
    }
}

echo "\nDone. Applied: $done, Skipped (already exist): $skipped\n";
echo "Run php scripts/check_db.php to verify.\n";
