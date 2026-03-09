<?php
// about.php - About Us page
$page_title = "About Us";
require_once 'header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>About Indiz Software</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>About</span>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-content">
                <h2>Your Trusted Technology Partner from Deoria, Gorakhpur</h2>
                <p class="lead">Founded with a mission to make enterprise-grade software accessible to businesses of all sizes across India and worldwide.</p>
                
                <p>Indiz Software Solution is a premier software development company based in Deoria, serving clients in Gorakhpur, Uttar Pradesh, and across the globe. With 5+ years of industry experience and 120+ successful projects, we've established ourselves as experts in ERP, POS, and E-commerce solutions.</p>
                
                <p>What started as a small team in Deoria has grown into a trusted technology partner for schools, retailers, and businesses across 12+ countries. Our deep understanding of local business needs, combined with global technology standards, allows us to deliver solutions that truly make a difference.</p>
                
                <div class="about-stats">
                    <div class="stat-box">
                        <span class="stat-number">120+</span>
                        <span class="stat-label">Projects</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Schools</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">100+</span>
                        <span class="stat-label">Retailers</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">12+</span>
                        <span class="stat-label">Countries</span>
                    </div>
                </div>
                
                <h3>Our Presence</h3>
                <p><i class="fas fa-map-marker-alt" style="color: #2563eb;"></i> <strong>Head Office:</strong> Deoria, Gorakhpur, Uttar Pradesh - 274001</p>
                <p><i class="fas fa-phone" style="color: #2563eb;"></i> <strong>Phone:</strong> <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a></p>
                <p><i class="fas fa-envelope" style="color: #2563eb;"></i> <strong>Email:</strong> <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></p>
            </div>
            
            <div class="about-image">
                <div class="image-card">
                    <i class="fas fa-building"></i>
                    <h3>Indiz Software</h3>
                    <p>Deoria, Gorakhpur, UP</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <h2 class="section-title">Our Expertise</h2>
        <p class="section-subtitle">We combine technical skills with business understanding</p>
        
        <div class="expertise-grid">
            <div class="expertise-item">
                <i class="fas fa-laptop-code"></i>
                <h4>ERP Specialists</h4>
                <p>School, Inventory, Business ERP</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-cash-register"></i>
                <h4>POS Experts</h4>
                <p>Retail & Restaurant POS Systems</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-shopping-cart"></i>
                <h4>E-commerce Pros</h4>
                <p>Custom Online Stores</p>
            </div>
            <div class="expertise-item">
                <i class="fas fa-mobile-alt"></i>
                <h4>Mobile Apps</h4>
                <p>Android & iOS Applications</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>