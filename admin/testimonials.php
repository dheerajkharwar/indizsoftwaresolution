<?php
require_once __DIR__ . '/includes/auth.php';

$testimonials = [];
try {
    $testimonials = $pdo->query("SELECT id, client_name, company, message, rating, created_at FROM testimonials ORDER BY created_at DESC LIMIT 200")->fetchAll();
} catch (Exception $e) {
}

$page_title = 'Testimonials';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="card-header"><h3><i class="fas fa-star"></i> Testimonials</h3></div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Client</th><th>Company</th><th>Rating</th><th>Message</th><th>Date</th></tr></thead>
                <tbody>
                    <?php if (empty($testimonials)): ?>
                        <tr><td colspan="6">No testimonials found.</td></tr>
                    <?php else: foreach ($testimonials as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['company'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars((string)$row['rating']); ?></td>
                            <td><?php echo htmlspecialchars((string)$row['message']); ?></td>
                            <td><?php echo htmlspecialchars((string)$row['created_at']); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
