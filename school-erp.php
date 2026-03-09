<?php
// school-erp.php - School ERP detailed page
$page_title = "School ERP System";
require_once 'header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>School ERP System</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / 
            <a href="services.php">Services</a> / 
            <span>School ERP</span>
        </div>
    </div>
</section>

<!-- Service Detail -->
<section class="service-detail">
    <div class="container">
        <div class="service-detail-grid">
            <div class="service-info">
                <h2>Complete School Management Solution</h2>
                <p>Transform your school administration with our comprehensive ERP system designed specifically for educational institutions in India.</p>
                
                <div class="feature-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-user-graduate"></i>
                        <div>
                            <h4>Student Information System</h4>
                            <p>Centralized database for admissions, profiles, documents, and academic history.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-calendar-check"></i>
                        <div>
                            <h4>Attendance Management</h4>
                            <p>Biometric, RFID, or manual attendance with SMS alerts to parents.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-file-alt"></i>
                        <div>
                            <h4>Examination & Grades</h4>
                            <p>Create exams, generate report cards, and publish results online.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-indian-rupee-sign"></i>
                        <div>
                            <h4>Fee Management</h4>
                            <p>Automated fee collection, receipts, due reminders, and online payments.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-chalkboard-user"></i>
                        <div>
                            <h4>Teacher Portal</h4>
                            <p>Lesson planning, assignments, and performance tracking.</p>
                        </div>
                    </div>
                    
                    <div class="highlight-item">
                        <i class="fas fa-bus"></i>
                        <div>
                            <h4>Transport & Library</h4>
                            <p>Manage routes, vehicles, library books, and issue tracking.</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-cta">
                    <h3>Get Free Demo for Your School</h3>
                    <p>Contact us for a personalized demo tailored to your school's needs.</p>
                    <a href="contact.php?service=school-erp" class="btn-primary">Request Demo →</a>
                </div>
            </div>
            
            <div class="service-sidebar">
                <div class="sidebar-card">
                    <h4>Why Schools Choose Us</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> 50+ Schools Trust Us</li>
                        <li><i class="fas fa-check-circle"></i> CBSE/ICSE/State Board Compatible</li>
                        <li><i class="fas fa-check-circle"></i> Mobile App for Parents</li>
                        <li><i class="fas fa-check-circle"></i> SMS & Email Integration</li>
                        <li><i class="fas fa-check-circle"></i> 24/7 Technical Support</li>
                        <li><i class="fas fa-check-circle"></i> Data Security & Backup</li>
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