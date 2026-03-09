<?php
// send-mail.php - Secure Email Handler

require_once 'config/app-config.php';
require_once 'config/database.php';
require_once 'config/security.php';
$pdo = requireDBConnection();

// Start secure session
secureSessionStart();

// Rate limiting (prevent spam)
if (!checkRateLimit('contact_form', 3, 300)) { // Max 3 submissions per 5 minutes
    $_SESSION['flash_message'] = "Too many attempts. Please wait 5 minutes.";
    $_SESSION['flash_type'] = "error";
    header("Location: contact.php");
    exit();
}

// CSRF Protection
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    securityLog("CSRF token validation failed", 'WARNING');
    $_SESSION['flash_message'] = "Invalid request. Please try again.";
    $_SESSION['flash_type'] = "error";
    header("Location: contact.php");
    exit();
}

// Honeypot check (spam prevention)
if (!empty($_POST['website']) || !empty($_POST['fax'])) {
    // Bot filled hidden field
    securityLog("Honeypot triggered - possible bot", 'WARNING');
    header("Location: thank-you.php"); // Redirect to fake thank you
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    securityLog("Invalid request method", 'WARNING');
    header("Location: contact.php");
    exit();
}

// Get and sanitize form data
$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$phone = sanitizeInput($_POST['phone'] ?? '');
$service = sanitizeInput($_POST['service'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Validation
$errors = [];

// Name validation
if (empty($name)) {
    $errors[] = "Name is required";
} elseif (strlen($name) < 2 || strlen($name) > 100) {
    $errors[] = "Name must be between 2 and 100 characters";
} elseif (preg_match('/[0-9]/', $name)) {
    $errors[] = "Name should not contain numbers";
}

// Email validation
if (empty($email)) {
    $errors[] = "Email is required";
} elseif (!validateEmail($email)) {
    $errors[] = "Valid email is required";
} elseif (strlen($email) > 100) {
    $errors[] = "Email is too long";
}

// Phone validation (optional but if provided, validate)
if (!empty($phone) && !validatePhone($phone)) {
    $errors[] = "Please enter a valid 10-digit Indian mobile number";
}

// Message validation
if (empty($message)) {
    $errors[] = "Message is required";
} elseif (strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters";
} elseif (strlen($message) > 2000) {
    $errors[] = "Message is too long (max 2000 characters)";
}

// Check for spam patterns
$spamPatterns = ['<a href', '[url=', 'http://', 'https://', 'www.', '.com', '.net', '.org'];
$messageLower = strtolower($message);
foreach ($spamPatterns as $pattern) {
    if (strpos($messageLower, $pattern) !== false) {
        $errors[] = "Message contains prohibited content";
        securityLog("Spam pattern detected: $pattern", 'WARNING');
        break;
    }
}

// If validation passes
if (empty($errors)) {
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert into database with prepared statement
        $sql = "INSERT INTO inquiries (name, email, phone, service, message, ip_address, user_agent, status, created_at) 
                VALUES (:name, :email, :phone, :service, :message, :ip_address, :user_agent, 'new', NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':service' => $service,
            ':message' => $message,
            ':ip_address' => $ip_address,
            ':user_agent' => $user_agent
        ]);
        
        $inquiryId = $pdo->lastInsertId();
        
        // Prepare email content
        $to = SITE_EMAIL;
        $subject = "New Contact Form Submission #$inquiryId - " . SITE_NAME;
        
        // Email content with security
        $emailContent = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .field { margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
                .label { font-weight: bold; color: #333; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .security-note { background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>New Inquiry #$inquiryId</h2>
            </div>
            <div class='content'>
                <div class='security-note'>
                    <strong>Security Info:</strong><br>
                    IP: " . htmlspecialchars($ip_address) . "<br>
                    Time: " . date('Y-m-d H:i:s') . "
                </div>
                
                <div class='field'>
                    <div class='label'>Name:</div>
                    <div>" . htmlspecialchars($name) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div>" . htmlspecialchars($email) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div>" . htmlspecialchars($phone) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Service Interested:</div>
                    <div>" . htmlspecialchars($service) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>This inquiry was sent from " . SITE_NAME . " contact form</p>
                <p>© " . date('Y') . " " . SITE_NAME . "</p>
            </div>
        </body>
        </html>
        ";
        
        // Send email (using PHP mail for simplicity - consider PHPMailer for production)
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . SITE_NAME . " <" . SITE_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        $mailSent = mail($to, $subject, $emailContent, $headers);
        
        if ($mailSent) {
            // Update inquiry with email status
            $updateStmt = $pdo->prepare("UPDATE inquiries SET email_sent = 1 WHERE id = ?");
            $updateStmt->execute([$inquiryId]);
            
            securityLog("Inquiry #$inquiryId sent successfully", 'INFO');
        } else {
            securityLog("Failed to send email for inquiry #$inquiryId", 'ERROR');
        }
        
        // Send auto-reply to user
        $userSubject = "Thank you for contacting " . SITE_NAME;
        $userContent = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2>Thank you for reaching out, " . htmlspecialchars($name) . "!</h2>
            <p>We have received your inquiry and will get back to you within 24 hours.</p>
            
            <div style='background: #f5f5f5; padding: 20px; margin: 20px 0;'>
                <h3>Your inquiry details:</h3>
                <p><strong>Service:</strong> " . htmlspecialchars($service) . "</p>
                <p><strong>Message:</strong> " . nl2br(htmlspecialchars($message)) . "</p>
                <p><strong>Reference #:</strong> " . $inquiryId . "</p>
            </div>
            
            <p>If you need immediate assistance, please call us at " . SITE_PHONE . ".</p>
            
            <hr>
            <p style='color: #666;'>
                <strong>" . SITE_NAME . "</strong><br>
                " . SITE_ADDRESS . "<br>
                Phone: " . SITE_PHONE . "<br>
                Email: " . SITE_EMAIL . "
            </p>
        </body>
        </html>
        ";
        
        $userHeaders = "MIME-Version: 1.0\r\n";
        $userHeaders .= "Content-type: text/html; charset=UTF-8\r\n";
        $userHeaders .= "From: " . SITE_NAME . " <" . SITE_EMAIL . ">\r\n";
        
        mail($email, $userSubject, $userContent, $userHeaders);
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['flash_message'] = "Thank you! Your message has been sent successfully. Reference #: $inquiryId";
        $_SESSION['flash_type'] = "success";
        
    } catch (PDOException $e) {
        // Rollback transaction
        $pdo->rollBack();
        
        // Log error securely
        securityLog("Database error: " . $e->getMessage(), 'ERROR');
        
        $_SESSION['flash_message'] = "An error occurred. Please try again later.";
        $_SESSION['flash_type'] = "error";
        
    } catch (Exception $e) {
        securityLog("General error: " . $e->getMessage(), 'ERROR');
        $_SESSION['flash_message'] = "An unexpected error occurred.";
        $_SESSION['flash_type'] = "error";
    }
    
} else {
    // Validation failed
    $_SESSION['flash_message'] = implode("<br>", $errors);
    $_SESSION['flash_type'] = "error";
    
    // Log validation failures
    securityLog("Validation failed: " . implode(", ", $errors), 'WARNING');
}

// Redirect back to contact page
header("Location: contact.php");
exit();
?>
