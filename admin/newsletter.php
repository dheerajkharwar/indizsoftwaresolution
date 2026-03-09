<?php
require_once __DIR__ . '/includes/auth.php';

$subscribers = [];
try {
    $subscribers = $pdo->query("SELECT id, email, created_at FROM newsletter_subscribers ORDER BY created_at DESC LIMIT 500")->fetchAll();
} catch (Exception $e) {
}

$page_title = 'Newsletter';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="card-header"><h3><i class="fas fa-newspaper"></i> Newsletter Subscribers</h3></div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Email</th><th>Subscribed At</th></tr></thead>
                <tbody>
                    <?php if (empty($subscribers)): ?>
                        <tr><td colspan="3">No subscribers found.</td></tr>
                    <?php else: foreach ($subscribers as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
