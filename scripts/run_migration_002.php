<?php
/**
 * Run eleven committees migration using PHP (no mysql CLI needed).
 * Run from project root: php scripts/run_migration_002.php
 */

$configPath = __DIR__ . '/../app/config/config.php';
if (!file_exists($configPath)) {
    echo "Config not found.\n";
    exit(1);
}
require $configPath;

$migrationFile = __DIR__ . '/../database/migrations/002_eleven_committees.sql';
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
$sql = preg_replace('/--[^\n]*\n/', "\n", $sql);
$sql = preg_replace('/^\s*USE\s+\w+\s*;/mi', '', $sql);
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
        if (strpos($e->getMessage(), 'already exists') !== false || strpos($e->getMessage(), 'Duplicate') !== false) {
            $skipped++;
            echo "s";
        } else {
            echo "\nError: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}

echo "\nDone. Applied: $done, Skipped: $skipped\n";
echo "Run php scripts/check_db.php to verify.\n";
