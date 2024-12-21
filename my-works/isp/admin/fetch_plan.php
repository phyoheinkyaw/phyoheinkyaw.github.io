<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Check if plan_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['plan_id'])) {
    $plan_id = intval($_GET['plan_id']);

    // Fetch the subscription plan details
    $query = "SELECT sp.plan_id, sp.plan_name, sp.plan_speed, sp.plan_price_per_month, sp.plan_features, sp.isp_id, i.isp_name 
              FROM subscription_plan sp
              JOIN isp i ON sp.isp_id = i.isp_id
              WHERE sp.plan_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $plan = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $plan]);
    } else {
        echo json_encode(["success" => false, "message" => "Subscription plan not found."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}