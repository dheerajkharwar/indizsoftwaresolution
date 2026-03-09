<?php
// index.php - Home page
$page_title = "Home";
require_once 'header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <h1>Transform Your Business with <span class="hero-highlight">Cutting-Edge Software Solutions</span></h1>
                <p>We deliver innovative web and software development services that drive growth, enhance productivity, and streamline operations for businesses worldwide.</p>
                
                <div class="hero-buttons">
                    <a href="contact.php" class="btn-primary">Start Your Project →</a>
                    <a href="services.php" class="btn-outline">Explore Solutions</a>
                </div>
                
                <!-- Stats from your original website -->
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">120+</span>
                        <span class="stat-label">Projects Delivered</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">5+ yrs</span>
                        <span class="stat-label">Experience</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-image">
                <div class="hero-image-content">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Indiz Software</h3>
                    <p>Deoria, Gorakhpur, UP</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="trust-badges">
    <div class="container">
        <div class="badges-grid">
            <span class="badge">🏫 50+ Schools</span>
            <span class="badge">🏪 100+ Retailers</span>
            <span class="badge">🛍️ 75+ E-commerce</span>
            <span class="badge">🌍 12+ Countries</span>
            <span class="badge">⭐ 4.9/5 Rating</span>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="services-preview">
    <div class="container">
        <h2 class="section-title">Our Complete Solutions</h2>
        <p class="section-subtitle">Specialized software for every industry need</p>
        
        <div class="services-grid">
            <!-- School ERP Card -->
            <div class="service-card">
                <div class="card-icon">
                    <i class="fas fa-school"></i>
                </div>
                <h3>School ERP</h3>
                <p>Complete school management with attendance, exams, fees, parent portal, and more.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Student Management</li>
                    <li><i class="fas fa-check"></i> Exam & Grades</li>
                    <li><i class="fas fa-check"></i> Fee Collection</li>
                    <li><i class="fas fa-check"></i> Parent Portal</li>
                </ul>
                <a href="school-erp.php" class="card-link">Learn More →</a>
            </div>
            
            <!-- Inventory & POS Card -->
            <div class="service-card">
                <div class="card-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <h3>Inventory & POS</h3>
                <p>Powerful retail management with real-time tracking, billing, and analytics.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Real-time Inventory</li>
                    <li><i class="fas fa-check"></i> Barcode Support</li>
                    <li><i class="fas fa-check"></i> Multi-store</li>
                    <li><i class="fas fa-check"></i> GST Billing</li>
                </ul>
                <a href="inventory-pos.php" class="card-link">Learn More →</a>
            </div>
            
            <!-- E-commerce Card -->
            <div class="service-card">
                <div class="card-icon">
                    <i class="fas fa-cart-shopping"></i>
                </div>
                <h3>E-commerce Websites</h3>
                <p>Custom online stores with payment integration, inventory sync, and marketing tools.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Responsive Design</li>
                    <li><i class="fas fa-check"></i> Payment Gateway</li>
                    <li><i class="fas fa-check"></i> Inventory Sync</li>
                    <li><i class="fas fa-check"></i> SEO Optimized</li>
                </ul>
                <a href="ecommerce.php" class="card-link">Learn More →</a>
            </div>
        </div>
        
        <div class="text-center" style="margin-top: 3rem;">
            <a href="services.php" class="btn-outline">View All Services <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Why Choose Us (Original from your website) -->
<section class="why-choose">
    <div class="container">
        <h2 class="section-title">Why Choose Us</h2>
        <p class="section-subtitle">We combine technical expertise with business understanding</p>
        
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h4>120+ Projects</h4>
                <p>Successful projects delivered with 5+ years of industry experience.</p>
            </div>
            
            <div class="why-card">
                <div class="why-icon">
                    <i class="far fa-clock"></i>
                </div>
                <h4>Timely Delivery</h4>
                <p>We respect deadlines and deliver quality work on time, every time.</p>
            </div>
            
            <div class="why-card">
                <div class="why-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h4>24/7 Support</h4>
                <p>Round-the-clock support to ensure your systems run smoothly.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section with your location -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Based in Deoria, Serving Worldwide</h2>
            <p>From Gorakhpur, Uttar Pradesh to global clients – we deliver excellence everywhere.</p>
            <div class="cta-buttons">
                <a href="tel:<?php echo SITE_PHONE; ?>" class="btn-primary">
                    <i class="fas fa-phone"></i> Call: <?php echo SITE_PHONE; ?>
                </a>
                <a href="contact.php" class="btn-outline-light">Get Free Consultation</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>