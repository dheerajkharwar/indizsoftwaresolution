<?php
// inventory-pos.php - Inventory & POS detailed page
$page_title = "Inventory & POS System";
require_once 'header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Inventory & POS System</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / 
            <a href="services.php">Services</a> / 
            <span>Inventory & POS</span>
        </div>
    </div>
</section>

<!-- Service Detail -->
<section class="service-detail">
    <div class="container">
        <div class="service-detail-grid">
            <div class="service-info">
                <h2>Powerful Retail Management Solution</h2>
                <p>Streamline your retail operations with our comprehensive POS and inventory system designed for Indian businesses.</p>
                
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-boxes"></i>
                        <div>
                            <h4>Real-time Inventory</h4>
                            <p>Track stock levels across multiple locations in real-time with low stock alerts.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-cash-register"></i>
                        <div>
                            <h4>Billing & Invoicing</h4>
                            <p>Fast billing with GST support, discount management, and multiple payment modes.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-barcode"></i>
                        <div>
                            <h4>Barcode Integration</h4>
                            <p>Generate and scan barcodes for quick product lookup and checkout.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <h4>Sales Analytics</h4>
                            <p>Detailed reports on sales, profit, bestsellers, and customer behavior.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-truck"></i>
                        <div>
                            <h4>Supplier Management</h4>
                            <p>Manage vendors, purchase orders, and stock replenishment.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-store"></i>
                        <div>
                            <h4>Multi-store Support</h4>
                            <p>Manage multiple outlets from a single dashboard with centralized control.</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-cta">
                    <h3>Transform Your Retail Business</h3>
                    <p>Get a free consultation and demo for your store.</p>
                    <a href="contact.php?service=inventory-pos" class="btn-primary">Get Started →</a>
                </div>
            </div>
            
            <div class="service-sidebar">
                <div class="sidebar-card">
                    <h4>Perfect For</h4>
                    <ul>
                        <li><i class="fas fa-store"></i> Retail Stores</li>
                        <li><i class="fas fa-utensils"></i> Restaurants</li>
                        <li><i class="fas fa-tshirt"></i> Clothing Stores</li>
                        <li><i class="fas fa-toolbox"></i> Hardware Shops</li>
                        <li><i class="fas fa-pharmacy"></i> Medical Stores</li>
                        <li><i class="fas fa-grocery-store"></i> Grocery & Supermarkets</li>
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