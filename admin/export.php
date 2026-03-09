<?php
require_once __DIR__ . '/includes/auth.php';

$type = sanitizeInput($_GET['type'] ?? '');
if ($type !== 'inquiries') {
    $_SESSION['error'] = 'Unsupported export type.';
    header('Location: dashboard.php');
    exit();
}

try {
    $stmt = $pdo->query("SELECT id, name, email, phone, service, status, created_at, ip_address FROM inquiries ORDER BY created_at DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $filename = 'inquiries_export_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Service', 'Status', 'Created At', 'IP Address']);
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = 'Unable to export inquiries.';
    header('Location: inquiries.php');
    exit();
}
?>
