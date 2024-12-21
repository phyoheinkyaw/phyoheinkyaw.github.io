<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $promotion_id = isset($_POST['promotion_id']) ? intval($_POST['promotion_id']) : null;
    $isp_id = isset($_POST['isp_id']) ? intval($_POST['isp_id']) : null;
    $promotion_text = isset($_POST['promotion_text']) ? trim($_POST['promotion_text']) : null;

    // Check if required fields are provided
    if (!$promotion_id || !$isp_id || !$promotion_text) {
        echo json_encode(["success" => false, "message" => "Required fields are missing."]);
        exit;
    }

    // Prepare and execute the update query
    $query = "UPDATE isp_promotion SET isp_id = ?, promotion_text = ? WHERE promotion_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $isp_id, $promotion_text, $promotion_id);

    // Respond based on query execution result
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Promotion updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update promotion: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}