<?php
// admin/backup.php - Database Backup
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Only admin can backup
requirePermission('admin');

$message = '';
$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'create') {
    try {
        // Get all tables
        $tables = [];
        $result = $pdo->query("SHOW TABLES");
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        $sql = "-- Database Backup for Indiz Software\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Server: " . DB_HOST . "\n\n";
        
        foreach ($tables as $table) {
            // Drop table if exists
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            
            // Create table
            $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
            $sql .= $create['Create Table'] . ";\n\n";
            
            // Get data
            $rows = $pdo->query("SELECT * FROM `$table`");
            $rows->setFetchMode(PDO::FETCH_ASSOC);
            
            while ($row = $rows->fetch()) {
                $columns = implode("`, `", array_keys($row));
                $values = array_map([$pdo, 'quote'], array_values($row));
                $values = implode(", ", $values);
                
                $sql .= "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
            }
            
            $sql .= "\n\n";
        }
        
        // Save backup
        $backup_dir = __DIR__ . '/../backups/';
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backup_dir . $filename;
        
        file_put_contents($filepath, $sql);
        
        // Log backup
        $log = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, 'BACKUP', ?, ?)");
        $log->execute([$_SESSION['admin_id'], "Database backup created: $filename", $_SERVER['REMOTE_ADDR']]);
        
        // Download file
        if (isset($_GET['download'])) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit();
        }
        
        $message = "Backup created successfully: $filename";
        
    } catch (Exception $e) {
        $error = "Backup failed: " . $e->getMessage();
    }
}

// List existing backups
$backups = [];
$backup_dir = __DIR__ . '/../backups/';
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir, SCANDIR_SORT_DESCENDING);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $backups[] = [
                'name' => $file,
                'size' => filesize($backup_dir . $file),
                'date' => filemtime($backup_dir . $file)
            ];
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="backup-container">
    <h1><i class="fas fa-database"></i> Database Backup</h1>
    
    <?php if ($message): ?>
    <div class="alert success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="backup-actions">
        <a href="?action=create" class="btn-primary">
            <i class="fas fa-plus-circle"></i> Create New Backup
        </a>
        <a href="?action=create&download=1" class="btn-secondary">
            <i class="fas fa-download"></i> Create & Download
        </a>
    </div>
    
    <div class="backup-info">
        <h3>Backup Information</h3>
        <ul>
            <li><i class="fas fa-check-circle"></i> Backups include all tables and data</li>
            <li><i class="fas fa-check-circle"></i> Files are stored in /backups directory</li>
            <li><i class="fas fa-check-circle"></i> Keep backups secure - they contain sensitive data</li>
            <li><i class="fas fa-check-circle"></i> Recommended: Download and store backups offline</li>
        </ul>
    </div>
    
    <?php if (!empty($backups)): ?>
    <div class="backup-list">
        <h3>Existing Backups</h3>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><?php echo $backup['name']; ?></td>
                    <td><?php echo round($backup['size'] / 1024, 2); ?> KB</td>
                    <td><?php echo date('d M Y H:i:s', $backup['date']); ?></td>
                    <td class="actions">
                        <a href="../backups/<?php echo $backup['name']; ?>" download class="btn-icon" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" onclick="deleteBackup('<?php echo $backup['name']; ?>')" class="btn-icon" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
.backup-container {
    padding: 20px;
}

.backup-container h1 {
    margin-bottom: 30px;
    font-size: 24px;
    color: #333;
}

.backup-container h1 i {
    color: #667eea;
    margin-right: 10px;
}

.backup-actions {
    margin-bottom: 30px;
    display: flex;
    gap: 10px;
}

.backup-info {
    background: #fff3cd;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.backup-info h3 {
    margin-bottom: 15px;
    color: #856404;
}

.backup-info ul {
    list-style: none;
}

.backup-info li {
    margin-bottom: 10px;
    color: #856404;
}

.backup-info i {
    margin-right: 10px;
    color: #10b981;
}

.backup-list {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.backup-list h3 {
    margin-bottom: 20px;
}

.actions {
    display: flex;
    gap: 5px;
}

@media (max-width: 768px) {
    .backup-actions {
        flex-direction: column;
    }
}
</style>

<script>
function deleteBackup(filename) {
    if (confirm('Are you sure you want to delete this backup?')) {
        window.location.href = 'delete-backup.php?file=' + encodeURIComponent(filename);
    }
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
