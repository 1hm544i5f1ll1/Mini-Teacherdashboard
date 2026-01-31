<?php
require_once __DIR__ . '/../app/config/config.php';

$host   = DB_HOST;
$port   = defined('DB_PORT') ? DB_PORT : 3306;
$user   = DB_USER;
$pass   = DB_PASS;
$dbname = DB_NAME;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "--- Resetting Passwords ---\n";

    // Generate proper password hashes
    $managerHash = password_hash('change_me', PASSWORD_DEFAULT);
    $teacherHash = password_hash('teacher123', PASSWORD_DEFAULT);

    // Update manager password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'manager'");
    $stmt->execute([$managerHash]);
    echo "✓ Manager password reset (username: manager, password: change_me)\n";

    // Update teacher password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'ahmed'");
    $stmt->execute([$teacherHash]);
    echo "✓ Teacher password reset (username: ahmed, password: teacher123)\n";

    // Verify the passwords work
    echo "\n--- Verifying Passwords ---\n";
    
    $stmt = $pdo->prepare("SELECT username, password FROM users WHERE username IN ('manager', 'ahmed')");
    $stmt->execute();
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        $testPassword = $user['username'] === 'manager' ? 'change_me' : 'teacher123';
        if (password_verify($testPassword, $user['password'])) {
            echo "✓ Password verified for user: {$user['username']}\n";
        } else {
            echo "✗ Password verification FAILED for user: {$user['username']}\n";
        }
    }

    echo "\n--- Password Reset Completed Successfully ---\n";
    echo "\nLogin Credentials:\n";
    echo "Manager - Username: manager, Password: change_me\n";
    echo "Teacher - Username: ahmed, Password: teacher123\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
