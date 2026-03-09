<?php
// 500.php - Server Error
$page_title = "Server Error";
require_once 'header.php';
?>

<section class="error-section">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>Something Went Wrong</h1>
            <p>We're experiencing technical difficulties. Our team has been notified.</p>
            
            <div class="error-details">
                <p>Error Code: 500 - Internal Server Error</p>
                <p>Time: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            
            <div class="error-actions">
                <a href="index.php" class="btn-primary">← Go to Homepage</a>
                <a href="contact.php" class="btn-outline">Report Problem</a>
            </div>
            
            <div class="contact-info">
                <h3>Need immediate help?</h3>
                <p>Call us: <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a></p>
                <p>Email: <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></p>
            </div>
        </div>
    </div>
</section>

<style>
.error-icon i {
    font-size: 5rem;
    color: #f59e0b;
    margin-bottom: 1rem;
}

.error-details {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 10px;
    margin: 2rem 0;
    font-family: monospace;
}

.contact-info {
    margin-top: 3rem;
    padding: 2rem;
    background: #eef2ff;
    border-radius: 15px;
}

.contact-info a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}
</style>

<?php require_once 'footer.php'; ?>