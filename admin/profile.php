<?php
// admin/profile.php - Admin Profile
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'My Profile';
$admin_id = $_SESSION['admin_id'];

// Get admin details
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

$message = '';
$error = '';

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = sanitizeInput($_POST['email']);
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Verify current password
        if (!password_verify($current_password, $admin['password_hash'])) {
            $error = "Current password is incorrect";
        } else {
            // Update email
            $update = $pdo->prepare("UPDATE admin_users SET email = ? WHERE id = ?");
            $update->execute([$email, $admin_id]);
            
            // Update password if provided
            if (!empty($new_password)) {
                if ($new_password === $confirm_password) {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $update = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
                    $update->execute([$password_hash, $admin_id]);
                    $message = "Profile updated successfully";
                } else {
                    $error = "New passwords do not match";
                }
            } else {
                $message = "Profile updated successfully";
            }
            
            // Refresh admin data
            $stmt->execute([$admin_id]);
            $admin = $stmt->fetch();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="profile-container">
    <h1><i class="fas fa-user-circle"></i> My Profile</h1>
    
    <?php if ($message): ?>
    <div class="alert success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="profile-title">
                    <h2><?php echo htmlspecialchars($admin['username']); ?></h2>
                    <p class="role-badge"><?php echo ucfirst($admin['role']); ?></p>
                </div>
            </div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-label">Member Since</span>
                    <span class="stat-value"><?php echo date('d M Y', strtotime($admin['created_at'])); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Last Login</span>
                    <span class="stat-value"><?php echo $admin['last_login'] ? date('d M Y h:i A', strtotime($admin['last_login'])) : 'Never'; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Last IP</span>
                    <span class="stat-value"><?php echo $admin['last_ip'] ?: 'N/A'; ?></span>
                </div>
            </div>
        </div>
        
        <div class="profile-card">
            <h3>Edit Profile</h3>
            
            <form method="POST" action="" class="profile-form">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled class="form-control">
                    <small>Username cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?php echo htmlspecialchars($admin['email']); ?>" 
                           required 
                           class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           required 
                           class="form-control">
                </div>
                
                <hr>
                
                <h4>Change Password (Optional)</h4>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-control">
                    <small>Leave empty to keep current password</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           class="form-control">
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="update_profile" class="btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
        
        <div class="profile-card">
            <h3>Security Recommendations</h3>
            
            <div class="security-checklist">
                <div class="check-item <?php echo strlen($admin['password_hash']) > 60 ? 'completed' : ''; ?>">
                    <i class="fas <?php echo strlen($admin['password_hash']) > 60 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <span>Strong password</span>
                </div>
                
                <div class="check-item <?php echo filter_var($admin['email'], FILTER_VALIDATE_EMAIL) ? 'completed' : ''; ?>">
                    <i class="fas <?php echo filter_var($admin['email'], FILTER_VALIDATE_EMAIL) ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <span>Valid email</span>
                </div>
                
                <div class="check-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>2FA (coming soon)</span>
                </div>
            </div>
            
            <div class="tips-box">
                <h4>Security Tips</h4>
                <ul>
                    <li>Use a strong password with letters, numbers, and symbols</li>
                    <li>Don't share your password with anyone</li>
                    <li>Change your password regularly</li>
                    <li>Logout when leaving the admin panel</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    padding: 20px;
}

.profile-container h1 {
    margin-bottom: 30px;
    font-size: 24px;
    color: #333;
}

.profile-container h1 i {
    color: #667eea;
    margin-right: 10px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert.success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #155724;
}

.alert.error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #721c24;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.profile-card:last-child {
    grid-column: span 2;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
}

.profile-title h2 {
    margin-bottom: 5px;
}

.role-badge {
    background: #667eea;
    color: white;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
    display: inline-block;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.stat-label {
    display: block;
    color: #666;
    font-size: 12px;
    margin-bottom: 5px;
}

.stat-value {
    font-weight: 600;
    color: #333;
}

.profile-form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #666;
    font-weight: 500;
    font-size: 13px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
}

.form-control:disabled {
    background: #f8f9fa;
    color: #999;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 11px;
}

hr {
    margin: 20px 0;
    border: none;
    border-top: 1px solid #eee;
}

.form-actions {
    margin-top: 20px;
}

.security-checklist {
    margin: 20px 0;
}

.check-item {
    padding: 10px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.check-item.completed i {
    color: #10b981;
}

.check-item i {
    width: 20px;
    color: #ef4444;
}

.tips-box {
    background: #fff3cd;
    padding: 15px;
    border-radius: 8px;
}

.tips-box h4 {
    margin-bottom: 10px;
    color: #856404;
}

.tips-box ul {
    margin-left: 20px;
    color: #856404;
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-card:last-child {
        grid-column: span 1;
    }
    
    .profile-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
