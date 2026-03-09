<?php
// footer.php - Common footer
?>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">INDIZ SOFTWARE</div>
                    <p class="footer-about">Delivering innovative software solutions since 2019. Specialized in ERP, POS, and E-commerce development.</p>
                    
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">→ Home</a></li>
                        <li><a href="about.php">→ About Us</a></li>
                        <li><a href="services.php">→ Services</a></li>
                        <li><a href="contact.php">→ Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="school-erp.php">→ School ERP</a></li>
                        <li><a href="inventory-pos.php">→ Inventory & POS</a></li>
                        <li><a href="ecommerce.php">→ E-commerce Websites</a></li>
                        <li><a href="services.php">→ Custom Software</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></li>
                        <li><i class="fas fa-phone"></i> <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></li>
                        <li><i class="fas fa-clock"></i> Mon-Sat: 9:00 AM - 7:00 PM</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | Deoria, Gorakhpur, Uttar Pradesh</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <?php $js_file = __DIR__ . '/js/main.js'; ?>
    <script src="js/main.js?v=<?php echo file_exists($js_file) ? filemtime($js_file) : time(); ?>"></script>
    
    <!-- Contact Form Success/Error Message -->
    <?php if(isset($_SESSION['flash_message'])): ?>
    <div class="flash-message flash-<?php echo $_SESSION['flash_type']; ?>" id="flashMessage">
        <?php 
            echo $_SESSION['flash_message']; 
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        ?>
    </div>
    <?php endif; ?>
    
</body>
</html>
