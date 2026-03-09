<?php
// admin/index.php - Admin Login Page
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
$pdo = getDB();

// Start secure session
secureSessionStart();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$pdo instanceof PDO) {
        $error = "Database is currently unavailable. Please try again later.";
    } else {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Rate limiting
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM failed_logins WHERE ip_address = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->execute([$ip]);
    $attempts = $stmt->fetchColumn();
    
    if ($attempts >= 5) {
        $error = "Too many failed attempts. Please try again after 15 minutes.";
        securityLog("Login rate limit exceeded for IP: $ip", 'WARNING');
    } else {
        // Check credentials
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE (username = ? OR email = ?) AND (locked_until IS NULL OR locked_until < NOW())");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_role'] = $user['role'];
            
            // Update last login
            $update = $pdo->prepare("UPDATE admin_users SET last_login = NOW(), last_ip = ?, login_attempts = 0 WHERE id = ?");
            $update->execute([$ip, $user['id']]);
            
            // Log activity
            $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'LOGIN', 'Admin login successful', ?)");
            $log->execute([$user['id'], $ip]);
            
            securityLog("Admin login successful: $username", 'INFO');
            
            header("Location: dashboard.php");
            exit();
        } else {
            // Failed login
            $error = "Invalid username or password";
            
            // Log failed attempt
            $log = $pdo->prepare("INSERT INTO failed_logins (username, ip_address) VALUES (?, ?)");
            $log->execute([$username, $ip]);
            
            // Lock account after 10 failed attempts
            $failCount = $pdo->prepare("UPDATE admin_users SET login_attempts = login_attempts + 1 WHERE username = ? OR email = ?");
            $failCount->execute([$username, $username]);
            
            // Lock for 30 minutes if attempts >= 10
            $lock = $pdo->prepare("UPDATE admin_users SET locked_until = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE (username = ? OR email = ?) AND login_attempts >= 10");
            $lock->execute([$username, $username]);
            
            securityLog("Failed login attempt: $username from IP: $ip", 'WARNING');
        }
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Indiz Software</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .logo {
            font-size: 40px;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c33;
        }
        
        .security-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
        }
        
        .security-info i {
            color: #667eea;
            margin-right: 5px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .password-input {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-cube"></i>
            </div>
            <h1>Admin Login</h1>
            <p>Indiz Software Solution</p>
        </div>
        
        <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       autofocus
                       placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Enter your password">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
            </div>
            
            <button type="submit" class="btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="security-info">
            <p><i class="fas fa-shield-alt"></i> Secure admin access only</p>
            <p><i class="fas fa-clock"></i> Session timeout: 30 minutes</p>
            <p><i class="fas fa-lock"></i> Max 5 attempts per 15 minutes</p>
        </div>
        
        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.querySelector('.toggle-password');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Prevent double submission
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('loginBtn').disabled = true;
            document.getElementById('loginBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        });
    </script>
</body>
</html>
