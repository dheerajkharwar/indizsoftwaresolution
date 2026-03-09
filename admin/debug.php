<?php
// admin/debug.php - Admin-only diagnostics
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

echo "<h2>Admin Diagnostics</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";

$files = [
    __DIR__ . '/../config/database.php',
    __DIR__ . '/../config/security.php',
    __DIR__ . '/includes/auth.php',
    __DIR__ . '/includes/header.php',
    __DIR__ . '/includes/sidebar.php',
    __DIR__ . '/includes/footer.php',
    __DIR__ . '/dashboard.php'
];

echo "<h3>File Check</h3>";
foreach ($files as $file) {
    $status = file_exists($file) ? 'OK' : 'MISSING';
    echo htmlspecialchars($file) . " - " . $status . "<br>";
}

echo "<h3>Database Check</h3>";
try {
    requireDBConnection()->query("SELECT 1");
    echo "Database connection - OK";
} catch (Exception $e) {
    echo "Database connection - FAILED";
}

echo "<h3>Session Data</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>
