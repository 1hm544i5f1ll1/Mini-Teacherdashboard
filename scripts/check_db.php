<?php
/**
 * Check if database exists and registration tables/columns are present.
 * Run from project root: php scripts/check_db.php
 */

$configPath = __DIR__ . '/../app/config/config.php';
if (!file_exists($configPath)) {
    echo "Config not found.\n";
    exit(1);
}
require $configPath;

$port = defined('DB_PORT') ? DB_PORT : 3306;
$dsn = "mysql:host=" . DB_HOST . ";port=$port;charset=utf8mb4";

echo "Checking " . DB_HOST . ":$port ...\n";

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // 1. Database exists?
    $stmt = $pdo->query("SHOW DATABASES LIKE '" . DB_NAME . "'");
    if ($stmt->rowCount() === 0) {
        echo "FAIL: Database '" . DB_NAME . "' does NOT exist.\n";
        echo "Create it: mysql -u root -p -e \"CREATE DATABASE " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\"\n";
        exit(1);
    }
    echo "OK: Database '" . DB_NAME . "' exists.\n";

    $pdo->exec("USE " . DB_NAME);

    // 2. Required tables?
    $tables = ['users', 'guardians', 'students', 'admissions', 'student_documents'];
    foreach ($tables as $t) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$t'");
        if ($stmt->rowCount() === 0) {
            echo "FAIL: Table '$t' does NOT exist. Run database/schema.sql\n";
            exit(1);
        }
    }
    echo "OK: Required tables exist.\n";

    // 3. Registration columns in admissions?
    $stmt = $pdo->query("SHOW COLUMNS FROM admissions LIKE 'academic_year'");
    if ($stmt->rowCount() === 0) {
        echo "WARN: Table 'admissions' missing column 'academic_year'. Run php scripts/run_migration.php\n";
        exit(1);
    }
    echo "OK: Registration migration applied (admissions.academic_year present).\n";

    // 4. Eleven committees tables?
    $stmt = $pdo->query("SHOW TABLES LIKE 'registration_committee_results'");
    if ($stmt->rowCount() === 0) {
        echo "WARN: Table 'registration_committee_results' missing. Run php scripts/run_migration_002.php\n";
        exit(1);
    }
    echo "OK: Eleven committees tables present.\n";

    echo "\nDatabase check passed. You can run the app.\n";
} catch (PDOException $e) {
    echo "FAIL: " . $e->getMessage() . "\n";
    echo "Check DB_HOST, DB_PORT, DB_USER, DB_PASS in app/config/config.php\n";
    exit(1);
}
