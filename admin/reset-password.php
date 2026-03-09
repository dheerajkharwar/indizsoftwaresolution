<?php
die;
// admin/reset-password.php - Run this once and DELETE immediately
require_once '../config/database.php';

$new_password = 'Admin@123';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE username = 'admin'");
    $stmt->execute([$hashed]);
    echo "Password updated successfully!<br>";
    echo "New password: " . $new_password . "<br>";
    echo "Hash: " . $hashed . "<br>";
    echo "<strong style='color:red'>DELETE THIS FILE NOW!</strong>";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>