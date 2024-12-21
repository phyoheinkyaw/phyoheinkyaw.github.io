<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Handle POST request for deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promotion_id'])) {
    $promotion_id = intval($_POST['promotion_id']);

    // Check if the promotion exists
    $query = "SELECT * FROM isp_promotion WHERE promotion_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $promotion_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Promotion not found."]);
        exit;
    }

    // Delete the promotion
    $deleteQuery = "DELETE FROM isp_promotion WHERE promotion_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $promotion_id);

    if ($deleteStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Promotion deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete promotion: " . $deleteStmt->error]);
    }

    $deleteStmt->close();
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}