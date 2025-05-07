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

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT username FROM users WHERE id = :user_id AND username = 'admin'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user_id", $_SESSION['user_id']);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "is_admin" => $stmt->rowCount() > 0
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?> 