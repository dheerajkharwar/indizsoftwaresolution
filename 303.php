<?php
// 403.php - Access Denied
$page_title = "Access Denied";
require_once 'header.php';
?>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h1>Access Denied</h1>
            <p>You don't have permission to access this page.</p>
            
            <div class="error-actions">
                <a href="index.php" class="btn-primary">← Go to Homepage</a>
                <a href="contact.php" class="btn-outline">Contact Support</a>
            </div>
        </div>
    </div>
</section>

<style>
.error-icon i {
    font-size: 5rem;
    color: #ef4444;
    margin-bottom: 1rem;
}
</style>

<?php require_once 'footer.php'; ?>