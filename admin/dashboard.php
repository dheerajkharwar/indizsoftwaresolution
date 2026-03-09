<?php
// admin/dashboard.php - Admin Dashboard
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Dashboard';

// Get statistics
$stats = [];

// Total inquiries
$stmt = $pdo->query("SELECT COUNT(*) FROM inquiries");
$stats['total_inquiries'] = $stmt->fetchColumn();

// New inquiries today
$stmt = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE DATE(created_at) = CURDATE()");
$stats['today_inquiries'] = $stmt->fetchColumn();

// New inquiries (unread)
$stmt = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'");
$stats['new_inquiries'] = $stmt->fetchColumn();

// Total subscribers
$stmt = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers");
$stats['total_subscribers'] = $stmt->fetchColumn();

// Recent inquiries
$stmt = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 10");
$recent_inquiries = $stmt->fetchAll();

// Inquiry by service
$stmt = $pdo->query("SELECT service, COUNT(*) as count FROM inquiries WHERE service != '' GROUP BY service ORDER BY count DESC");
$service_stats = $stmt->fetchAll();

// Activity log
$stmt = $pdo->query("SELECT * FROM activity_log ORDER BY created_at DESC LIMIT 10");
$activities = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="dashboard-container">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div>
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
            <p>Here's what's happening with your business today.</p>
        </div>
        <div class="date-display">
            <i class="fas fa-calendar"></i>
            <?php echo date('l, F j, Y'); ?>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo number_format($stats['total_inquiries']); ?></h3>
                <p>Total Inquiries</p>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> +<?php echo $stats['today_inquiries']; ?> today
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo $stats['new_inquiries']; ?></h3>
                <p>New (Unread)</p>
            </div>
            <div class="stat-change">
                <i class="fas fa-clock"></i> Need attention
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo number_format($stats['total_subscribers']); ?></h3>
                <p>Newsletter Subscribers</p>
            </div>
            <div class="stat-change positive">
                <i class="fas fa-user-plus"></i> Active
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-details">
                <h3><?php echo count($activities); ?></h3>
                <p>Recent Activities</p>
            </div>
            <div class="stat-change">
                <i class="fas fa-history"></i> Last 24h
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <!-- Recent Inquiries -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-inbox"></i> Recent Inquiries</h3>
                <a href="inquiries.php" class="btn-small">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_inquiries as $inquiry): ?>
                        <tr>
                            <td>#<?php echo $inquiry['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($inquiry['name']); ?>
                                <br><small><?php echo htmlspecialchars($inquiry['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($inquiry['service'] ?: 'General'); ?></td>
                            <td>
                                <?php
                                $status_class = [
                                    'new' => 'badge-warning',
                                    'read' => 'badge-info',
                                    'replied' => 'badge-success',
                                    'spam' => 'badge-danger'
                                ];
                                $class = $status_class[$inquiry['status']] ?? 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $class; ?>">
                                    <?php echo ucfirst($inquiry['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d M Y', strtotime($inquiry['created_at'])); ?>
                                <br><small><?php echo date('h:i A', strtotime($inquiry['created_at'])); ?></small>
                            </td>
                            <td>
                                <a href="inquiry-view.php?id=<?php echo $inquiry['id']; ?>" class="btn-icon" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Service Statistics -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Inquiries by Service</h3>
            </div>
            
            <div class="service-stats">
                <?php foreach ($service_stats as $stat): ?>
                <div class="stat-item">
                    <div class="stat-label">
                        <span><?php echo htmlspecialchars($stat['service'] ?: 'General'); ?></span>
                        <span class="stat-count"><?php echo $stat['count']; ?></span>
                    </div>
                    <div class="progress-bar">
                        <?php 
                        $percentage = ($stat['count'] / max($stats['total_inquiries'], 1)) * 100;
                        ?>
                        <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Recent Activity</h3>
            </div>
            
            <div class="activity-feed">
                <?php foreach ($activities as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-circle"></i>
                    </div>
                    <div class="activity-details">
                        <p><?php echo htmlspecialchars($activity['action']); ?></p>
                        <small>
                            <i class="fas fa-clock"></i> 
                            <?php echo date('d M Y h:i A', strtotime($activity['created_at'])); ?>
                            <?php if ($activity['ip_address']): ?>
                            <span class="ip-address">IP: <?php echo $activity['ip_address']; ?></span>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            
            <div class="quick-actions">
                <a href="inquiries.php?status=new" class="action-btn">
                    <i class="fas fa-envelope"></i>
                    <span>View New Inquiries</span>
                    <?php if ($stats['new_inquiries'] > 0): ?>
                    <span class="badge"><?php echo $stats['new_inquiries']; ?></span>
                    <?php endif; ?>
                </a>
                
                <a href="backup.php" class="action-btn">
                    <i class="fas fa-database"></i>
                    <span>Backup Database</span>
                </a>
                
                <a href="settings.php" class="action-btn">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                
                <a href="profile.php" class="action-btn">
                    <i class="fas fa-user"></i>
                    <span>Edit Profile</span>
                </a>
                
                <a href="../index.php" target="_blank" class="action-btn">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Website</span>
                </a>
                
                <a href="logout.php" class="action-btn" onclick="return confirm('Are you sure you want to logout?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 20px;
}

.welcome-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.welcome-banner h1 {
    font-size: 28px;
    margin-bottom: 10px;
}

.date-display {
    background: rgba(255,255,255,0.2);
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: relative;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 24px;
}

.stat-details h3 {
    font-size: 28px;
    margin-bottom: 5px;
}

.stat-details p {
    color: #666;
    font-size: 14px;
}

.stat-change {
    width: 100%;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    font-size: 13px;
    color: #666;
}

.stat-change.positive {
    color: #10b981;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.dashboard-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.card-header h3 {
    font-size: 18px;
    color: #333;
}

.card-header h3 i {
    color: #667eea;
    margin-right: 10px;
}

.btn-small {
    padding: 5px 15px;
    background: #f0f0f0;
    border-radius: 20px;
    color: #666;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s;
}

.btn-small:hover {
    background: #667eea;
    color: white;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 10px;
    font-size: 13px;
    color: #666;
    font-weight: 600;
    border-bottom: 2px solid #f0f0f0;
}

.data-table td {
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.data-table tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.badge-info {
    background: #d1ecf1;
    color: #0c5460;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.btn-icon {
    padding: 5px 10px;
    border-radius: 5px;
    color: #666;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-icon:hover {
    background: #667eea;
    color: white;
}

.service-stats {
    padding: 10px;
}

.stat-item {
    margin-bottom: 15px;
}

.stat-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 14px;
}

.stat-count {
    font-weight: 600;
    color: #667eea;
}

.progress-bar {
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 4px;
}

.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: start;
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    margin-right: 15px;
    color: #667eea;
    font-size: 8px;
}

.activity-details p {
    margin-bottom: 5px;
    font-size: 14px;
}

.activity-details small {
    color: #999;
    font-size: 12px;
}

.ip-address {
    margin-left: 10px;
    color: #667eea;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.action-btn {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s;
    position: relative;
}

.action-btn:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.action-btn i {
    font-size: 20px;
    margin-bottom: 8px;
}

.action-btn span {
    font-size: 13px;
}

.action-btn .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
}

@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .welcome-banner {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
