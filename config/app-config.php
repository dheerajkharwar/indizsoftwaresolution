<?php
// app-config.php - Global app constants

// Optional runtime overrides from config/site-settings.json
$settings = [];
$settingsFile = __DIR__ . '/site-settings.json';
if (file_exists($settingsFile)) {
    $decoded = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($decoded)) {
        $settings = $decoded;
    }
}

// Site Configuration
defined('SITE_NAME') || define('SITE_NAME', $settings['site_name'] ?? 'Indiz Software Solution');
defined('SITE_EMAIL') || define('SITE_EMAIL', $settings['site_email'] ?? 'info@indizsoftwaresolution.com');
defined('SITE_PHONE') || define('SITE_PHONE', $settings['site_phone'] ?? '+91 7526018014');
defined('SITE_ADDRESS') || define('SITE_ADDRESS', $settings['site_address'] ?? 'Deoria, Gorakhpur, Uttar Pradesh - 274001');
defined('SITE_URL') || define('SITE_URL', $settings['site_url'] ?? 'http://localhost/indiz-software'); // Change to your domain

// Email Configuration (using PHPMailer)
defined('SMTP_HOST') || define('SMTP_HOST', 'smtp.gmail.com'); // or your SMTP server
defined('SMTP_PORT') || define('SMTP_PORT', 587);
defined('SMTP_USER') || define('SMTP_USER', 'your-email@gmail.com'); // Your Gmail
defined('SMTP_PASS') || define('SMTP_PASS', 'your-app-password'); // Gmail App Password
defined('SMTP_FROM') || define('SMTP_FROM', 'noreply@indizsoftwaresolution.com');
defined('SMTP_FROM_NAME') || define('SMTP_FROM_NAME', 'Indiz Software Solution');


// Function to sanitize input
if (!function_exists('sanitize')) {
    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}
?>
