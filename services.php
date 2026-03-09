<?php
// services.php - Main services overview page
$page_title = "Our Services";
require_once 'header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Our Software Solutions</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Services</span>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="services-overview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Complete Business Solutions</h2>
            <p class="section-subtitle">We deliver tailored software solutions for every industry need</p>
        </div>
        
        <div class="services-grid-large">
            <!-- School ERP -->
            <div class="service-large-card">
                <div class="service-large-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="service-large-content">
                    <h3>School ERP System</h3>
                    <p>Complete digital transformation for educational institutions. Manage students, staff, fees, exams, and communication from one platform.</p>
                    <div class="service-features">
                        <span><i class="fas fa-check-circle"></i> Student Information</span>
                        <span><i class="fas fa-check-circle"></i> Attendance Tracking</span>
                        <span><i class="fas fa-check-circle"></i> Exam Management</span>
                        <span><i class="fas fa-check-circle"></i> Fee Collection</span>
                        <span><i class="fas fa-check-circle"></i> Parent Portal</span>
                        <span><i class="fas fa-check-circle"></i> Library Management</span>
                    </div>
                    <a href="school-erp.php" class="btn-outline">Learn More →</a>
                </div>
            </div>
            
            <!-- Inventory & POS -->
            <div class="service-large-card">
                <div class="service-large-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="service-large-content">
                    <h3>Inventory & POS System</h3>
                    <p>Streamline your retail operations with real-time inventory tracking, fast billing, and detailed analytics.</p>
                    <div class="service-features">
                        <span><i class="fas fa-check-circle"></i> Real-time Stock</span>
                        <span><i class="fas fa-check-circle"></i> Barcode Integration</span>
                        <span><i class="fas fa-check-circle"></i> GST Billing</span>
                        <span><i class="fas fa-check-circle"></i> Multi-store Support</span>
                        <span><i class="fas fa-check-circle"></i> Supplier Management</span>
                        <span><i class="fas fa-check-circle"></i> Sales Analytics</span>
                    </div>
                    <a href="inventory-pos.php" class="btn-outline">Learn More →</a>
                </div>
            </div>
            
            <!-- E-commerce -->
            <div class="service-large-card">
                <div class="service-large-icon">
                    <i class="fas fa-cart-shopping"></i>
                </div>
                <div class="service-large-content">
                    <h3>E-commerce Websites</h3>
                    <p>Launch your online store with custom-designed e-commerce solutions that drive sales and growth.</p>
                    <div class="service-features">
                        <span><i class="fas fa-check-circle"></i> Responsive Design</span>
                        <span><i class="fas fa-check-circle"></i> Payment Integration</span>
                        <span><i class="fas fa-check-circle"></i> Inventory Sync</span>
                        <span><i class="fas fa-check-circle"></i> SEO Optimized</span>
                        <span><i class="fas fa-check-circle"></i> Marketing Tools</span>
                        <span><i class="fas fa-check-circle"></i> Order Management</span>
                    </div>
                    <a href="ecommerce.php" class="btn-outline">Learn More →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Services -->
<section class="additional-services">
    <div class="container">
        <h2 class="section-title">Other Solutions We Offer</h2>
        <div class="services-mini-grid">
            <div class="service-mini">
                <i class="fas fa-laptop-medical"></i>
                <h4>Hospital Management</h4>
            </div>
            <div class="service-mini">
                <i class="fas fa-hotel"></i>
                <h4>Hotel Management</h4>
            </div>
            <div class="service-mini">
                <i class="fas fa-truck"></i>
                <h4>Logistics Software</h4>
            </div>
            <div class="service-mini">
                <i class="fas fa-chart-line"></i>
                <h4>CRM Systems</h4>
            </div>
            <div class="service-mini">
                <i class="fas fa-mobile-alt"></i>
                <h4>Mobile Apps</h4>
            </div>
            <div class="service-mini">
                <i class="fas fa-cloud"></i>
                <h4>Cloud Solutions</h4>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="process-section">
    <div class="container">
        <h2 class="section-title">Our Development Process</h2>
        <div class="process-steps">
            <div class="process-step">
                <div class="step-number">1</div>
                <h4>Requirement Analysis</h4>
                <p>We understand your business needs and goals</p>
            </div>
            <div class="process-step">
                <div class="step-number">2</div>
                <h4>Planning & Design</h4>
                <p>Create blueprint and UI/UX design</p>
            </div>
            <div class="process-step">
                <div class="step-number">3</div>
                <h4>Development</h4>
                <p>Agile development with regular updates</p>
            </div>
            <div class="process-step">
                <div class="step-number">4</div>
                <h4>Testing</h4>
                <p>Rigorous quality assurance</p>
            </div>
            <div class="process-step">
                <div class="step-number">5</div>
                <h4>Deployment</h4>
                <p>Launch and go-live support</p>
            </div>
            <div class="process-step">
                <div class="step-number">6</div>
                <h4>Maintenance</h4>
                <p>24/7 support and updates</p>
            </div>
        </div>
    </div>
</section>

<!-- Technologies -->
<section class="technologies">
    <div class="container">
        <h2 class="section-title">Technologies We Work With</h2>
        <div class="tech-grid">
            <span class="tech-badge">PHP</span>
            <span class="tech-badge">MySQL</span>
            <span class="tech-badge">Laravel</span>
            <span class="tech-badge">CodeIgniter</span>
            <span class="tech-badge">JavaScript</span>
            <span class="tech-badge">React</span>
            <span class="tech-badge">Node.js</span>
            <span class="tech-badge">Python</span>
            <span class="tech-badge">HTML5</span>
            <span class="tech-badge">CSS3</span>
            <span class="tech-badge">Bootstrap</span>
            <span class="tech-badge">AJAX</span>
            <span class="tech-badge">jQuery</span>
            <span class="tech-badge">REST API</span>
            <span class="tech-badge">AWS</span>
            <span class="tech-badge">Docker</span>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Transform Your Business?</h2>
            <p>Get a free consultation and quote for your project</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn-primary">Start Your Project</a>
                <a href="tel:<?php echo SITE_PHONE; ?>" class="btn-outline-light">Call: <?php echo SITE_PHONE; ?></a>
            </div>
        </div>
    </div>
</section>

<style>
/* Additional CSS for services page */
.services-grid-large {
    display: flex;
    flex-direction: column;
    gap: 3rem;
    margin: 3rem 0;
}

.service-large-card {
    display: grid;
    grid-template-columns: 100px 1fr;
    gap: 2rem;
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    align-items: start;
}

.service-large-icon {
    background: #eef2ff;
    width: 100px;
    height: 100px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #2563eb;
}

.service-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.8rem;
    margin: 1.5rem 0;
}

.service-features span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.service-features i {
    color: #2563eb;
    font-size: 0.9rem;
}

.services-mini-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1.5rem;
    margin: 2rem 0;
}

.service-mini {
    background: #f8fafc;
    padding: 2rem 1rem;
    border-radius: 15px;
    text-align: center;
    transition: transform 0.3s;
}

.service-mini:hover {
    transform: translateY(-5px);
    background: #eef2ff;
}

.service-mini i {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 1rem;
}

.service-mini h4 {
    font-size: 1rem;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 1rem;
    margin: 3rem 0;
}

.process-step {
    text-align: center;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.step-number {
    width: 50px;
    height: 50px;
    background: #2563eb;
    color: white;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto 1rem;
}

.tech-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin: 2rem 0;
}

.tech-badge {
    background: #eef2ff;
    color: #2563eb;
    padding: 0.5rem 1.5rem;
    border-radius: 30px;
    font-weight: 500;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .service-large-card {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .service-large-icon {
        margin: 0 auto;
    }
    
    .service-features {
        grid-template-columns: 1fr;
    }
    
    .services-mini-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .process-steps {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'footer.php'; ?>