<?php
session_start();
header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Handle the AJAX request
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['isp_id']) && isset($input['is_featured'])) {
    $isp_id = intval($input['isp_id']);
    $is_featured = intval($input['is_featured']);

    // Update the is_featured value in the database
    $query = "UPDATE isp SET is_featured = ? WHERE isp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $is_featured, $isp_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Featured status updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update featured status: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();