<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=eventomate", "root", "vishal@2005");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        // Create admin user with hashed password
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES ('admin', 'admin@eventomate.com', :password, TRUE)");
        $stmt->bindParam(":password", $hashed_password);
        $stmt->execute();
        echo "Admin user created successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Admin user already exists.\n";
    }
} catch(PDOException $e) {
    error_log("Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}
?> 