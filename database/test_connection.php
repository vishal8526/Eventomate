<?php
require_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        echo "Database connection successful!<br>";
        
        // Test query
        $query = "SELECT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        echo "Test query executed successfully!<br>";
        echo "Database is ready for use.";
    } else {
        echo "Database connection failed!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Please check your database configuration in config/database.php";
}
?> 