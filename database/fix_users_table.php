<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=eventomate", "root", "vishal@2005");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Drop and recreate the users table with the correct structure
    $pdo->exec("DROP TABLE IF EXISTS users");
    
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        is_admin BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "Users table recreated successfully!\n";

    // Verify the table structure
    $result = $pdo->query("DESCRIBE users");
    echo "\nUpdated users table structure:\n";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Field: " . $row['Field'] . 
             " | Type: " . $row['Type'] . 
             " | Null: " . $row['Null'] . 
             " | Key: " . $row['Key'] . 
             " | Default: " . $row['Default'] . "\n";
    }

    // Create admin user
    $admin_username = 'admin';
    $admin_email = 'admin@eventomate.com';
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, TRUE)");
    $stmt->execute([$admin_username, $admin_email, $admin_password]);
    echo "\nAdmin user created successfully!\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
} finally {
    // Make sure foreign key checks are re-enabled even if an error occurs
    if (isset($pdo)) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
}
?> 