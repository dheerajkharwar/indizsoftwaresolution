<?php
// admin/includes/auth.php - Authentication and authorization helpers
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/security.php';

secureSessionStart();
$pdo = requireDBConnection();

// Check if user is logged in
if (empty($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Get admin details
try {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if (!$admin) {
        session_unset();
        session_destroy();
        header("Location: index.php?error=invalid_user");
        exit();
    }
} catch (PDOException $e) {
    error_log("Admin auth DB error: " . $e->getMessage());
    session_unset();
    session_destroy();
    header("Location: index.php?error=auth_failed");
    exit();
}

function hasPermission($requiredRole) {
    global $admin;

    $roleOrder = [
        'viewer' => 1,
        'manager' => 2,
        'admin' => 3
    ];

    $userRole = $admin['role'] ?? 'viewer';
    if (!isset($roleOrder[$requiredRole], $roleOrder[$userRole])) {
        return false;
    }

    return $roleOrder[$userRole] >= $roleOrder[$requiredRole];
}

function requirePermission($requiredRole) {
    if (!hasPermission($requiredRole)) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        header("Location: dashboard.php");
        exit();
    }
}
?>
