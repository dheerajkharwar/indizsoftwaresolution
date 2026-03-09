<?php
// admin/logout.php - Logout
require_once __DIR__ . '/../config/security.php';

secureSessionStart();

// Log activity if user was logged in
if (isset($_SESSION['admin_id'])) {
    require_once __DIR__ . '/../config/database.php';
    $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'LOGOUT', 'Admin logout', ?)");
    $log->execute([$_SESSION['admin_id'], $_SERVER['REMOTE_ADDR'] ?? '']);
}

// Destroy session
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect to login
header("Location: index.php?logged_out=1");
exit();
?>