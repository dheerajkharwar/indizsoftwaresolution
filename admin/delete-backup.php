<?php
require_once __DIR__ . '/includes/auth.php';
requirePermission('admin');

$file = basename($_GET['file'] ?? '');
if ($file === '' || pathinfo($file, PATHINFO_EXTENSION) !== 'sql') {
    $_SESSION['error'] = 'Invalid backup file.';
    header('Location: backup.php');
    exit();
}

$path = __DIR__ . '/../backups/' . $file;
if (!file_exists($path)) {
    $_SESSION['error'] = 'Backup file not found.';
    header('Location: backup.php');
    exit();
}

if (@unlink($path)) {
    $_SESSION['success'] = "Backup deleted: $file";
} else {
    $_SESSION['error'] = 'Unable to delete backup file.';
}

header('Location: backup.php');
exit();
?>
