<?php
// thank-you.php - Thank you page after form submission
$page_title = "Thank You";
require_once 'header.php';
?>

<section class="thank-you-section">
    <div class="container">
        <div class="thank-you-content">
            <div class="success-animation">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Thank You for Contacting Us!</h1>
            <p class="thank-you-message">We have received your inquiry and will get back to you within 24 hours.</p>
            
            <div class="reference-card">
                <h3>Your Reference Information</h3>
                <p>Please save this reference number for future communication:</p>
                <div class="ref-number">
                    <?php 
                    // Generate a random reference if coming from form
                    echo 'IND-' . date('Ymd') . '-' . rand(1000, 9999); 
                    ?>
                </div>
            </div>
            
            <div class="next-steps">
                <h3>What Happens Next?</h3>
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-icon">1</div>
                        <h4>Confirmation Email</h4>
                        <p>You'll receive an email with your inquiry details</p>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">2</div>
                        <h4>Review</h4>
                        <p>Our team will review your requirements</p>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">3</div>
                        <h4>Contact</h4>
                        <p>We'll reach out to discuss your project</p>
                    </div>
                </div>
            </div>
            
            <div class="help-options">
                <h3>Need Immediate Assistance?</h3>
                <div class="help-grid">
                    <a href="tel:<?php echo SITE_PHONE; ?>" class="help-card">
                        <i class="fas fa-phone"></i>
                        <h4>Call Us</h4>
                        <p><?php echo SITE_PHONE; ?></p>
                    </a>
                    <a href="https://wa.me/91<?php echo SITE_PHONE; ?>" class="help-card" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        <h4>WhatsApp</h4>
                        <p>Chat with us</p>
                    </a>
                    <a href="mailto:<?php echo SITE_EMAIL; ?>" class="help-card">
                        <i class="fas fa-envelope"></i>
                        <h4>Email</h4>
                        <p><?php echo SITE_EMAIL; ?></p>
                    </a>
                </div>
            </div>
            
            <div class="return-options">
                <a href="index.php" class="btn-primary">← Back to Home</a>
                <a href="services.php" class="btn-outline">Explore Our Services</a>
            </div>
        </div>
    </div>
</section>

<style>
.thank-you-section {
    min-height: 60vh;
    padding: 4rem 0;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
}

.thank-you-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.success-animation i {
    font-size: 5rem;
    color: #10b981;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    70% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.thank-you-content h1 {
    font-size: 2.5rem;
    margin: 1.5rem 0 1rem;
    color: #0f172a;
}

.thank-you-message {
    font-size: 1.2rem;
    color: #475569;
    margin-bottom: 2rem;
}

.reference-card {
    background: #eef2ff;
    padding: 2rem;
    border-radius: 20px;
    margin: 2rem 0;
    border: 2px dashed #2563eb;
}

.ref-number {
    font-size: 2rem;
    font-weight: bold;
    color: #2563eb;
    background: white;
    padding: 1rem;
    border-radius: 10px;
    display: inline-block;
    margin-top: 1rem;
    font-family: monospace;
    letter-spacing: 2px;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin: 2rem 0;
}

.step-item {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.step-icon {
    width: 50px;
    height: 50px;
    background: #2563eb;
    color: white;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto 1rem;
}

.help-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin: 2rem 0;
}

.help-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s;
    border: 1px solid #e2e8f0;
}

.help-card:hover {
    transform: translateY(-5px);
    border-color: #2563eb;
}

.help-card i {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 1rem;
}

.return-options {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 3rem;
}

@media (max-width: 768px) {
    .steps-grid,
    .help-grid {
        grid-template-columns: 1fr;
    }
    
    .return-options {
        flex-direction: column;
    }
}
</style>

<?php require_once 'footer.php'; ?>