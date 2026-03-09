<?php
// admin/includes/footer.php - Admin panel footer
?>
            </div> <!-- .content-wrapper -->
        </div> <!-- .main-content -->
    </div> <!-- .admin-container -->
    
    <!-- Admin JavaScript -->
    <?php $admin_js = __DIR__ . '/../assets/js/admin.js'; ?>
    <script src="assets/js/admin.js?v=<?php echo file_exists($admin_js) ? filemtime($admin_js) : time(); ?>"></script>
    
    <!-- Auto-hide alerts -->
    <script>
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
