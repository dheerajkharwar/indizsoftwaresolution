<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../config/app-config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inquiries.php');
    exit();
}

$inquiryId = (int)($_POST['inquiry_id'] ?? 0);
$toEmail = sanitizeInput($_POST['to_email'] ?? '');
$toName = sanitizeInput($_POST['to_name'] ?? '');
$subject = sanitizeInput($_POST['subject'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');

if ($inquiryId <= 0 || !validateEmail($toEmail) || $subject === '' || $message === '') {
    $_SESSION['error'] = 'Invalid reply payload.';
    header('Location: inquiry-view.php?id=' . max(0, $inquiryId));
    exit();
}

$body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$html = "<html><body><p>Dear " . htmlspecialchars($toName, ENT_QUOTES, 'UTF-8') . ",</p><p>$body</p></body></html>";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . SITE_NAME . " <" . SITE_EMAIL . ">\r\n";

$sent = @mail($toEmail, $subject, $html, $headers);

try {
    $status = $sent ? 'replied' : 'read';
    $stmt = $pdo->prepare("UPDATE inquiries SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$status, $inquiryId]);

    $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'REPLY', ?, ?)");
    $log->execute([$_SESSION['admin_id'], "Reply sent for inquiry #$inquiryId", $_SERVER['REMOTE_ADDR'] ?? '']);
} catch (Exception $e) {
}

$_SESSION['success'] = $sent ? 'Reply sent successfully.' : 'Reply could not be sent by mail() on this server.';
header('Location: inquiry-view.php?id=' . $inquiryId);
exit();
?>
