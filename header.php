<?php
// header.php with security integration
require_once __DIR__ . '/config/app-config.php';
require_once __DIR__ . '/config/security.php';

// Start secure session
secureSessionStart();

// Generate CSRF token for forms
$csrf_token = generateCSRFToken();

// Get current page for active menu
$current_page = basename($_SERVER['PHP_SELF']);

// Security headers already set in .htaccess, but ensure they're sent
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Security meta tags -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Font Awesome with SRI for security -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer">

    <!-- Custom CSS -->
    <?php $css_file = __DIR__ . '/css/style.css'; ?>
    <link rel="stylesheet" href="css/style.css?v=<?php echo file_exists($css_file) ? filemtime($css_file) : time(); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <a href="index.php" class="logo">
                    INDIZ SOFTWARE
                    <span>solution - innovation - impact</span>
                </a>

                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a></li>

                    <li class="dropdown">
                        <a href="services.php" class="<?php echo in_array($current_page, ['services.php', 'school-erp.php', 'inventory-pos.php', 'ecommerce.php']) ? 'active' : ''; ?>">Services <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="school-erp.php">School ERP</a></li>
                            <li><a href="inventory-pos.php">Inventory & POS</a></li>
                            <li><a href="ecommerce.php">E-commerce Websites</a></li>
                        </ul>
                    </li>

                    <li><a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a></li>

                    <li><a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>

                    <li><a href="contact.php" class="btn-outline">Get Quote</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <main>
