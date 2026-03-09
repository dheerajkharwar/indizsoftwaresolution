<?php
// contact.php - Secure contact page
$page_title = "Contact Us";
require_once 'header.php';

// Get service from URL if passed
$selected_service = isset($_GET['service']) ? sanitizeInput($_GET['service']) : '';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Contact</span>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p class="contact-description">Have a project in mind? We'd love to discuss how we can help your business grow.</p>
                
                <div class="info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Visit Us</h4>
                        <p><?php echo htmlspecialchars(SITE_ADDRESS); ?></p>
                        <p class="small"><i class="fas fa-clock"></i> Mon-Sat, 9:00 AM - 7:00 PM</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Call Us</h4>
                        <p><a href="tel:<?php echo htmlspecialchars(SITE_PHONE); ?>"><?php echo htmlspecialchars(SITE_PHONE); ?></a></p>
                        <p class="small">24/7 support for existing clients</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email Us</h4>
                        <p><a href="mailto:<?php echo htmlspecialchars(SITE_EMAIL); ?>"><?php echo htmlspecialchars(SITE_EMAIL); ?></a></p>
                        <p class="small">We reply within 24 hours</p>
                    </div>
                </div>
                
                <div class="security-badges">
                    <span class="badge"><i class="fas fa-lock"></i> SSL Secured</span>
                    <span class="badge"><i class="fas fa-shield-alt"></i> Privacy Protected</span>
                    <span class="badge"><i class="fas fa-clock"></i> 24/7 Support</span>
                </div>
                
                <div class="map-container">
                    <h4>Our Location</h4>
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114166.11176073063!2d83.4262899!3d26.5051952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399144c9aae6a8c1%3A0x7b5e5d0a5c5b0f0!2sDeoria%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        title="Indiz Software Location">
                    </iframe>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-container">
                <h2>Send a Message</h2>
                <p class="form-description">Fill out the form and we'll get back to you shortly.</p>
                
                <form action="send-mail.php" method="POST" class="contact-form" id="contactForm">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <!-- Honeypot fields (hidden from real users) -->
                    <div style="display: none;">
                        <label for="website">Website (do not fill)</label>
                        <input type="text" name="website" id="website" autocomplete="off">
                        <label for="fax">Fax (do not fill)</label>
                        <input type="text" name="fax" id="fax" autocomplete="off">
                    </div>
                    
                    <!-- Time-based token (optional extra security) -->
                    <input type="hidden" name="form_time" value="<?php echo time(); ?>">
                    
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required 
                               maxlength="100"
                               pattern="[A-Za-z\s]+"
                               title="Please enter only letters and spaces"
                               placeholder="Enter your full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               maxlength="100"
                               placeholder="you@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="7526018014" 
                               pattern="[0-9]{10}"
                               maxlength="10"
                               title="Please enter 10 digit mobile number"
                               placeholder="7526018014">
                        <small class="form-text">10 digit mobile number</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="service">Service Interested In</label>
                        <select id="service" name="service">
                            <option value="">Select a service</option>
                            <option value="School ERP" <?php echo $selected_service == 'school-erp' ? 'selected' : ''; ?>>School ERP</option>
                            <option value="Inventory & POS" <?php echo $selected_service == 'inventory-pos' ? 'selected' : ''; ?>>Inventory & POS</option>
                            <option value="E-commerce Website" <?php echo $selected_service == 'ecommerce' ? 'selected' : ''; ?>>E-commerce Website</option>
                            <option value="Other">Other / Custom Software</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5" 
                                  required 
                                  minlength="10"
                                  maxlength="2000"
                                  placeholder="Tell us about your project requirements..."></textarea>
                        <small class="form-text">Minimum 10 characters, maximum 2000</small>
                    </div>
                    
                    <!-- reCAPTCHA placeholder (optional - add Google reCAPTCHA) -->
                    <!-- <div class="form-group g-recaptcha" data-sitekey="your-site-key"></div> -->
                    
                    <div class="form-group">
                        <button type="submit" class="btn-primary btn-full" id="submitBtn">
                            Send Message <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <p class="privacy-note">
                        <i class="fas fa-shield-alt"></i> Your information is secure and will not be shared.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info Bar -->
<section class="contact-info-bar">
    <div class="container">
        <div class="info-bar-grid">
            <div class="info-bar-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Response Time</h4>
                    <p>Within 24 hours</p>
                </div>
            </div>
            <div class="info-bar-item">
                <i class="fas fa-headset"></i>
                <div>
                    <h4>Support</h4>
                    <p>24/7 for clients</p>
                </div>
            </div>
            <div class="info-bar-item">
                <i class="fas fa-handshake"></i>
                <div>
                    <h4>Free Consultation</h4>
                    <p>No obligation</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Client-side validation and security
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Sending... <i class="fas fa-spinner fa-spin"></i>';
    
    // Prevent double submission
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send Message <i class="fas fa-paper-plane"></i>';
    }, 10000); // Re-enable after 10 seconds
});

// Phone number validation
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});
</script>

<?php require_once 'footer.php'; ?>