<?php
require_once __DIR__ . '/../app/config/config.php';

$host   = DB_HOST;          // use 127.0.0.1
$port   = defined('DB_PORT') ? DB_PORT : 3306; // add DB_PORT in config
$user   = DB_USER;
$pass   = DB_PASS;
$dbname = DB_NAME;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "--- Starting Installation ---\n";

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`"); // <-- FIXED (removed 'text')

    $schemaFile = __DIR__ . '/../database/schema.sql';
    if (!file_exists($schemaFile)) throw new Exception("Schema file not found at $schemaFile");
    $sql = file_get_contents($schemaFile);
    $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS.*/i', '', $sql);
    $sql = preg_replace('/USE .*/i', '', $sql);
    $pdo->exec($sql);

    $seedFile = __DIR__ . '/../database/seed.sql';
    if (file_exists($seedFile)) {
        $sql = file_get_contents($seedFile);
        $sql = preg_replace('/USE .*/i', '', $sql);
        $pdo->exec($sql);
    }

    echo "--- Installation Completed Successfully ---\n";

} catch (PDOException $e) {
    die("Installation failed: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
