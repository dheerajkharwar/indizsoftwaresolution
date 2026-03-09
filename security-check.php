<?php
// security-check.php - Setup diagnostics (remove after use)
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/security.php';

$checks = [];
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$db = getDB();

$checks[] = [
    'name' => 'PHP Version',
    'status' => version_compare(PHP_VERSION, '8.0.0', '>=') ? 'PASS' : 'WARNING',
    'message' => 'Current: ' . PHP_VERSION . ' (8.0+ recommended)'
];

$checks[] = [
    'name' => 'HTTPS',
    'status' => $isHttps ? 'PASS' : 'WARNING',
    'message' => $isHttps ? 'HTTPS enabled' : 'HTTPS not enabled'
];

if ($db instanceof PDO) {
    $checks[] = [
        'name' => 'Database',
        'status' => 'PASS',
        'message' => 'Database connection successful'
    ];
} else {
    $checks[] = [
        'name' => 'Database',
        'status' => 'FAIL',
        'message' => 'Database connection unavailable'
    ];
}

$files = ['.htaccess', 'config/database.php', 'config/security.php'];
foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    $checks[] = [
        'name' => 'File: ' . $file,
        'status' => $exists ? 'PASS' : 'FAIL',
        'message' => $exists ? 'File exists' : 'File missing'
    ];
}

$logsWritable = is_writable(__DIR__ . '/logs');
$checks[] = [
    'name' => 'Logs Directory',
    'status' => $logsWritable ? 'PASS' : 'WARNING',
    'message' => $logsWritable ? 'Writable' : 'Not writable'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Check</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 20px;">
    <h1>Security Check for Indiz Software</h1>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th align="left">Check</th>
                <th align="left">Status</th>
                <th align="left">Message</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checks as $check): ?>
                <?php
                $color = '#ef4444';
                if ($check['status'] === 'PASS') {
                    $color = '#10b981';
                } elseif ($check['status'] === 'WARNING') {
                    $color = '#f59e0b';
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($check['name']); ?></td>
                    <td style="color: <?php echo $color; ?>; font-weight: bold;"><?php echo htmlspecialchars($check['status']); ?></td>
                    <td><?php echo htmlspecialchars($check['message']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p style="margin-top: 20px; color: #b91c1c; font-weight: bold;">Remove this file after review.</p>
</body>
</html>
