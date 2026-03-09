<?php
// admin/includes/header.php - Admin panel header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Admin | Indiz Software</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Admin CSS -->
    <?php $admin_css = __DIR__ . '/../assets/css/admin.css'; ?>
    <link rel="stylesheet" href="assets/css/admin.css?v=<?php echo file_exists($admin_css) ? filemtime($admin_css) : time(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content">
            <div class="top-header">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="header-right">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="notificationBadge">0</span>
                    </div>
                    
                    <div class="admin-profile dropdown">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['username']); ?>&background=2563eb&color=fff" alt="Admin">
                        <span><?php echo htmlspecialchars($admin['username']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                        
                        <div class="dropdown-menu">
                            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                            <a href="profile.php"><i class="fas fa-key"></i> Change Password</a>
                            <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-wrapper">
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <?php 
                            echo $_SESSION['success']; 
                            unset($_SESSION['success']);
                        ?>
                        <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" id="errorAlert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']);
                        ?>
                        <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                <?php endif; ?>
