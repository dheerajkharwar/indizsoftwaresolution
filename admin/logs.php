<?php
require_once __DIR__ . '/includes/auth.php';
requirePermission('admin');

$activities = [];
$failed = [];
try {
    $activities = $pdo->query("SELECT user_id, action, details, ip_address, created_at FROM activity_log ORDER BY created_at DESC LIMIT 200")->fetchAll();
} catch (Exception $e) {
}
try {
    $failed = $pdo->query("SELECT username, ip_address, attempted_at FROM failed_logins ORDER BY attempted_at DESC LIMIT 200")->fetchAll();
} catch (Exception $e) {
}

$page_title = 'Activity Logs';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-container">
    <div class="dashboard-card" style="margin-bottom:20px;">
        <div class="card-header"><h3><i class="fas fa-history"></i> Activity Log</h3></div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>User ID</th><th>Action</th><th>Details</th><th>IP</th><th>Time</th></tr></thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr><td colspan="5">No activity records found.</td></tr>
                    <?php else: foreach ($activities as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars((string)$row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['action']); ?></td>
                            <td><?php echo htmlspecialchars($row['details'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['ip_address'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header"><h3><i class="fas fa-shield-alt"></i> Failed Login Attempts</h3></div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Username</th><th>IP</th><th>Time</th></tr></thead>
                <tbody>
                    <?php if (empty($failed)): ?>
                        <tr><td colspan="3">No failed attempts found.</td></tr>
                    <?php else: foreach ($failed as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['attempted_at']); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
