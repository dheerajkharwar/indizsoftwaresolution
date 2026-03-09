<?php
require_once __DIR__ . '/../config/database.php';

$pdo = requireDBConnection();

$queries = [
    "CREATE TABLE IF NOT EXISTS admin_users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(190) NOT NULL UNIQUE,
        role ENUM('admin', 'manager', 'viewer') NOT NULL DEFAULT 'viewer',
        password_hash VARCHAR(255) NOT NULL,
        login_attempts INT UNSIGNED NOT NULL DEFAULT 0,
        locked_until DATETIME NULL,
        last_login DATETIME NULL,
        last_ip VARCHAR(45) NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS inquiries (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(190) NOT NULL,
        phone VARCHAR(30) NULL,
        service VARCHAR(120) NULL,
        message TEXT NOT NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        status ENUM('new', 'read', 'replied', 'spam') NOT NULL DEFAULT 'new',
        email_sent TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_status (status),
        INDEX idx_created_at (created_at),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS activity_log (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NULL,
        action VARCHAR(100) NOT NULL,
        details TEXT NULL,
        ip_address VARCHAR(45) NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_action (action),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS failed_logins (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(190) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ip_attempted_at (ip_address, attempted_at),
        INDEX idx_username (username)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(190) NOT NULL UNIQUE,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    "CREATE TABLE IF NOT EXISTS testimonials (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(120) NOT NULL,
        company VARCHAR(120) NULL,
        message TEXT NOT NULL,
        rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

try {
    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }

    $adminUsername = 'admin';
    $adminEmail = 'admin@example.com';
    $tempPassword = 'Admin@123';

    $check = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $check->execute([$adminUsername]);
    $exists = (bool)$check->fetchColumn();

    if (!$exists) {
        $insert = $pdo->prepare("INSERT INTO admin_users (username, email, role, password_hash, created_at) VALUES (?, ?, 'admin', ?, NOW())");
        $insert->execute([$adminUsername, $adminEmail, password_hash($tempPassword, PASSWORD_DEFAULT)]);
        echo "Created default admin user.\n";
        echo "Username: {$adminUsername}\n";
        echo "Password: {$tempPassword}\n";
        echo "Please change this password immediately after login.\n";
    } else {
        echo "Admin user already exists. No default user created.\n";
    }

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables present:\n";
    foreach ($tables as $table) {
        echo "- {$table}\n";
    }

    echo "Database setup completed successfully.\n";
} catch (Throwable $e) {
    fwrite(STDERR, "Database setup failed: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
?>
