<?php
require_once __DIR__ . '/includes/auth.php';
requirePermission('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['error'] = 'Invalid inquiry ID.';
    header('Location: inquiries.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);

    $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'INQUIRY_DELETE', ?, ?)");
    $log->execute([$_SESSION['admin_id'], "Deleted inquiry #$id", $_SERVER['REMOTE_ADDR'] ?? '']);

    $_SESSION['success'] = "Inquiry #$id deleted.";
} catch (Exception $e) {
    $_SESSION['error'] = 'Unable to delete inquiry.';
}

header('Location: inquiries.php');
exit();
?>
