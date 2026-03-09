<?php
require_once __DIR__ . '/includes/auth.php';
requirePermission('admin');

$settingsFile = __DIR__ . '/../config/site-settings.json';
$defaults = [
    'site_name' => 'Indiz Software Solution',
    'site_email' => 'info@indizsoftwaresolution.com',
    'site_phone' => '+91 7526018014',
    'site_address' => 'Deoria, Gorakhpur, Uttar Pradesh - 274001'
];

$settings = $defaults;
if (file_exists($settingsFile)) {
    $decoded = json_decode((string)file_get_contents($settingsFile), true);
    if (is_array($decoded)) {
        $settings = array_merge($settings, $decoded);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings['site_name'] = sanitizeInput($_POST['site_name'] ?? $settings['site_name']);
    $settings['site_email'] = sanitizeInput($_POST['site_email'] ?? $settings['site_email']);
    $settings['site_phone'] = sanitizeInput($_POST['site_phone'] ?? $settings['site_phone']);
    $settings['site_address'] = sanitizeInput($_POST['site_address'] ?? $settings['site_address']);

    if (!validateEmail($settings['site_email'])) {
        $_SESSION['error'] = 'Please provide a valid site email.';
    } else {
        if (!is_dir(dirname($settingsFile))) {
            mkdir(dirname($settingsFile), 0755, true);
        }
        file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $_SESSION['success'] = 'Settings updated.';
        header('Location: settings.php');
        exit();
    }
}

$page_title = 'Settings';
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-cog"></i> Site Settings</h3>
        </div>
        <form method="post" style="max-width:700px;">
            <div class="form-group">
                <label>Site Name</label>
                <input class="form-control" type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Site Email</label>
                <input class="form-control" type="email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Site Phone</label>
                <input class="form-control" type="text" name="site_phone" value="<?php echo htmlspecialchars($settings['site_phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Site Address</label>
                <textarea class="form-control" name="site_address" rows="3" required><?php echo htmlspecialchars($settings['site_address']); ?></textarea>
            </div>
            <button class="btn-login" type="submit"><i class="fas fa-save"></i> Save Settings</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
