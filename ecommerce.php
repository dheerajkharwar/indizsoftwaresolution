<?php
// ecommerce.php - E-commerce detailed page
$page_title = "E-commerce Solutions";
require_once 'header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>E-commerce Solutions</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / 
            <a href="services.php">Services</a> / 
            <span>E-commerce Websites</span>
        </div>
    </div>
</section>

<!-- Service Detail -->
<section class="service-detail">
    <div class="container">
        <div class="service-detail-grid">
            <div class="service-info">
                <h2>Custom E-commerce Websites</h2>
                <p>Launch your online store with our feature-rich e-commerce solutions designed to maximize sales.</p>
                
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <h4>Responsive Design</h4>
                            <p>Mobile-optimized stores that work perfectly on all devices.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-credit-card"></i>
                        <div>
                            <h4>Payment Integration</h4>
                            <p>Razorpay, Paytm, PhonePe, UPI, credit cards, and COD support.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-sync"></i>
                        <div>
                            <h4>Inventory Sync</h4>
                            <p>Real-time sync with your physical store inventory (POS integration).</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-search"></i>
                        <div>
                            <h4>SEO Optimized</h4>
                            <p>Built-in SEO features to rank higher on Google.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-tags"></i>
                        <div>
                            <h4>Marketing Tools</h4>
                            <p>Discount coupons, offers, abandoned cart recovery, and email marketing.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-chart-bar"></i>
                        <div>
                            <h4>Analytics Dashboard</h4>
                            <p>Track sales, customers, traffic sources, and conversion rates.</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-cta">
                    <h3>Ready to Start Selling Online?</h3>
                    <p>Get your custom e-commerce website built by experts.</p>
                    <a href="contact.php?service=ecommerce" class="btn-primary">Start Your Store →</a>
                </div>
            </div>
            
            <div class="service-sidebar">
                <div class="sidebar-card">
                    <h4>Features Included</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Unlimited Products</li>
                        <li><i class="fas fa-check-circle"></i> Order Management</li>
                        <li><i class="fas fa-check-circle"></i> Customer Accounts</li>
                        <li><i class="fas fa-check-circle"></i> Wishlist & Reviews</li>
                        <li><i class="fas fa-check-circle"></i> Shipping Integration</li>
                        <li><i class="fas fa-check-circle"></i> GST Invoice</li>
                    </ul>
                </div>
                
                <div class="sidebar-card">
                    <h4>Quick Contact</h4>
                    <p><i class="fas fa-phone"></i> <?php echo SITE_PHONE; ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo SITE_EMAIL; ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>