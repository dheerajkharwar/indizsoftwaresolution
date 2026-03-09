<?php
require_once __DIR__ . '/includes/auth.php';
requirePermission('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $role = sanitizeInput($_POST['role'] ?? 'viewer');
    $password = $_POST['password'] ?? '';

    $allowedRoles = ['admin', 'manager', 'viewer'];
    if ($username === '' || !validateEmail($email) || $password === '' || !in_array($role, $allowedRoles, true)) {
        $_SESSION['error'] = 'Invalid user details.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, role, password_hash, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$username, $email, $role, password_hash($password, PASSWORD_DEFAULT)]);
            $_SESSION['success'] = 'User created.';
            header('Location: users.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'Unable to create user (username/email may already exist).';
        }
    }
}

$users = [];
try {
    $users = $pdo->query("SELECT id, username, email, role, created_at, last_login FROM admin_users ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
}

$page_title = 'Users';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-container">
    <div class="dashboard-card" style="margin-bottom:20px;">
        <div class="card-header"><h3><i class="fas fa-user-plus"></i> Add Admin User</h3></div>
        <form method="post" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:12px;">
            <input class="form-control" type="text" name="username" placeholder="Username" required>
            <input class="form-control" type="email" name="email" placeholder="Email" required>
            <select class="form-control" name="role">
                <option value="viewer">Viewer</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
            </select>
            <input class="form-control" type="password" name="password" placeholder="Password" required>
            <button class="btn-login" type="submit" style="grid-column: span 2;">Create User</button>
        </form>
    </div>

    <div class="dashboard-card">
        <div class="card-header"><h3><i class="fas fa-users"></i> Admin Users</h3></div>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Last Login</th></tr></thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo (int)$user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_login'] ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
