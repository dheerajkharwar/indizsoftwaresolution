<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$status = sanitizeInput($_GET['status'] ?? '');
$allowed = ['new', 'read', 'replied', 'spam'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: inquiries.php');
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE inquiries SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $id]);

    $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'STATUS_UPDATE', ?, ?)");
    $log->execute([$_SESSION['admin_id'], "Inquiry #$id marked as $status", $_SERVER['REMOTE_ADDR'] ?? '']);

    $_SESSION['success'] = "Inquiry #$id marked as " . ucfirst($status) . '.';
} catch (Exception $e) {
    $_SESSION['error'] = 'Unable to update inquiry status.';
}

header('Location: inquiry-view.php?id=' . $id);
exit();
?>
