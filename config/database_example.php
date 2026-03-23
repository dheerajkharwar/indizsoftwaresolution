<?php
// config/database.php - Secure Database Configuration

// Define secure database credentials (store outside webroot in production)
// defined('DB_HOST') || define('DBs_PASS', getenv('DB_PASS') ?: 'ASD2233C45%#$aSQ'); // Strong password
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');
defined('DB_COLLATION') || define('DB_COLLATION', 'utf8mb4_unicode_ci');
// for local host db credentials
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_NAME') || define('DB_NAME', 'indiz_software');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
// PDO Connection options for security
$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch as associative array
    PDO::ATTR_EMULATE_PREPARES => false, // Disable emulated prepares (use real ones)
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    PDO::MYSQL_ATTR_FOUND_ROWS => true,
    PDO::ATTR_PERSISTENT => false // Don't use persistent connections
];

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdo_options);
    
} catch(PDOException $e) {
    // Log error but don't display to user
    error_log("Database Connection Error: " . $e->getMessage());
    $pdo = null;
}

// Function to get database connection
function getDB() {
    global $pdo;
    return $pdo;
}

function requireDBConnection() {
    $db = getDB();
    if ($db instanceof PDO) {
        return $db;
    }

    http_response_code(503);
    die("We're experiencing technical difficulties. Please try again later.");
}
?>
