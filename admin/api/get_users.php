<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Not logged in"
        ]);
        exit;
    }

    // Verify admin status
    $database = new Database();
    $db = $database->getConnection();

    $check_admin = "SELECT username FROM users WHERE id = :user_id AND username = 'admin'";
    $stmt = $db->prepare($check_admin);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized access"
        ]);
        exit;
    }

    // Get all users
    $query = "SELECT id, username, email, created_at FROM users ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add online status (this is a simple implementation - you might want to use a more sophisticated method)
    foreach ($users as &$user) {
        $user['is_online'] = false; // You can implement actual online status checking here
    }

    echo json_encode([
        "status" => "success",
        "users" => $users
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?> 