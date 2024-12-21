<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan_id'])) {
    $plan_id = intval($_POST['plan_id']);

    // Check if the plan exists
    $query = "SELECT * FROM subscription_plan WHERE plan_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Subscription plan not found."]);
        exit;
    }

    // Delete the plan
    $deleteQuery = "DELETE FROM subscription_plan WHERE plan_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $plan_id);

    if ($deleteStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Subscription plan deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete subscription plan: " . $deleteStmt->error]);
    }

    $deleteStmt->close();
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}