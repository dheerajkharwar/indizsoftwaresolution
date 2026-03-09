<?php
// config/security.php - Core Security Functions

// Start secure session
function secureSessionStart() {
    // Set secure session parameters
    ini_set('session.cookie_httponly', 1);
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // Regenerate session ID to prevent fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
    
    // Set session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    
    $_SESSION['last_activity'] = time();
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Input Sanitization
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Validate Email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

// Validate Phone (Indian format)
function validatePhone($phone) {
    // Remove any non-digit characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if it's a valid Indian mobile number
    return preg_match('/^[6-9][0-9]{9}$/', $phone);
}

// SQL Injection Prevention (use prepared statements, but this is extra)
function sanitizeForSQL($data) {
    if (is_array($data)) {
        return array_map('sanitizeForSQL', $data);
    }
    return str_replace(['"', "'", ";", "--"], '', $data);
}

// XSS Prevention
function escapeOutput($data) {
    if (is_array($data)) {
        return array_map('escapeOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Log security events
function securityLog($message, $type = 'INFO') {
    $logFile = __DIR__ . '/../logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    
    $logEntry = "[$timestamp] [$type] [IP: $ip] [UA: $userAgent] - $message" . PHP_EOL;
    
    // Rotate log if too large (>10MB)
    if (file_exists($logFile) && filesize($logFile) > 10 * 1024 * 1024) {
        rename($logFile, $logFile . '.' . date('Y-m-d_H-i-s') . '.old');
    }
    
    error_log($logEntry, 3, $logFile);
}

// Rate Limiting
function checkRateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$action}_{$ip}";
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 1,
            'first_attempt' => time()
        ];
        return true;
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window passed
    if (time() - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'count' => 1,
            'first_attempt' => time()
        ];
        return true;
    }
    
    // Check attempts
    if ($data['count'] >= $maxAttempts) {
        securityLog("Rate limit exceeded for $action", 'WARNING');
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}

// Secure file upload check
function isSecureFile($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    // Check for malicious content in images
    if (strpos($file['type'], 'image/') === 0) {
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return false;
        }
    }
    
    return true;
}

// Generate secure random password
function generateSecurePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+';
    return substr(str_shuffle($chars), 0, $length);
}
?>
