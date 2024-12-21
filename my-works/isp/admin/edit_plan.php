<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Handle POST requests for editing a plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form fields
    $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : null;
    $isp_id = isset($_POST['isp_id']) ? intval($_POST['isp_id']) : null;
    $plan_name = isset($_POST['plan_name']) ? trim($_POST['plan_name']) : null;
    $plan_speed = isset($_POST['plan_speed']) ? trim($_POST['plan_speed']) : null;
    $plan_price_per_month = isset($_POST['plan_price_per_month']) ? floatval($_POST['plan_price_per_month']) : null;
    $plan_features = isset($_POST['plan_features']) ? trim($_POST['plan_features']) : null;

    // Check if all required fields are provided
    if (!$plan_id || !$isp_id || !$plan_name || !$plan_speed || !$plan_price_per_month || !$plan_features) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Update the plan in the database
    $query = "UPDATE subscription_plan 
              SET isp_id = ?, plan_name = ?, plan_speed = ?, plan_price_per_month = ?, plan_features = ? 
              WHERE plan_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdsi", $isp_id, $plan_name, $plan_speed, $plan_price_per_month, $plan_features, $plan_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Subscription plan updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update subscription plan: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}