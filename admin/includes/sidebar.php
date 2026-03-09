<?php
// admin/includes/sidebar.php - Admin sidebar navigation

// Get current page for active menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            INDIZ
            <span>ADMIN</span>
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="admin-info">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin['username']); ?>&background=2563eb&color=fff&size=128" alt="Admin">
        <h4><?php echo htmlspecialchars($admin['username']); ?></h4>
        <span class="role-badge role-<?php echo htmlspecialchars($admin['role']); ?>">
            <?php echo ucfirst($admin['role']); ?>
        </span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item <?php echo in_array($current_page, ['inquiries.php', 'inquiry-view.php']) ? 'active' : ''; ?>">
                <a href="inquiries.php">
                    <i class="fas fa-envelope"></i>
                    <span>Inquiries</span>
                    <?php
                    // Get unread count
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'");
                        $unread = $stmt->fetchColumn();
                        if ($unread > 0) {
                            echo '<span class="nav-badge">' . (int)$unread . '</span>';
                        }
                    } catch (Exception $e) {
                    }
                    ?>
                </a>
            </li>

            <li class="nav-item <?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?>">
                <a href="testimonials.php">
                    <i class="fas fa-star"></i>
                    <span>Testimonials</span>
                </a>
            </li>

            <li class="nav-item <?php echo $current_page == 'newsletter.php' ? 'active' : ''; ?>">
                <a href="newsletter.php">
                    <i class="fas fa-newspaper"></i>
                    <span>Newsletter</span>
                </a>
            </li>

            <li class="nav-divider"></li>

            <li class="nav-item <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <?php if (hasPermission('admin')): ?>
            <li class="nav-item <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                <a href="users.php">
                    <i class="fas fa-users-cog"></i>
                    <span>Manage Users</span>
                </a>
            </li>

            <li class="nav-item <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <a href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <li class="nav-item <?php echo $current_page == 'logs.php' ? 'active' : ''; ?>">
                <a href="logs.php">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <p>&copy; <?php echo date('Y'); ?> Indiz Software</p>
        <p class="version">v1.0.0</p>
    </div>
</aside>
