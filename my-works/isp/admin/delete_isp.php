<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Check if ISP ID is provided
    if (!isset($data->isp_id)) {
        echo json_encode(["success" => false, "message" => "ISP ID is required."]);
        exit;
    }

    $isp_id = intval($data->isp_id);

    // First, get the photo path before deleting the ISP
    $photo_query = "SELECT isp_photo FROM isp WHERE isp_id = ?";
    $photo_stmt = $conn->prepare($photo_query);
    $photo_stmt->bind_param("i", $isp_id);
    $photo_stmt->execute();
    $photo_result = $photo_stmt->get_result();
    $photo_row = $photo_result->fetch_assoc();

    // Check if the ISP has related records (e.g., appointments)
    $check_related_query = "SELECT COUNT(*) as count FROM appointment WHERE isp_id = ?";
    $stmt = $conn->prepare($check_related_query);
    $stmt->bind_param("i", $isp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If related records exist, prevent deletion
    if ($row['count'] > 0) {
        echo json_encode(["success" => false, "message" => "Cannot delete ISP. There are related appointments."]);
        exit;
    }

    // Proceed with deletion if no related records
    $delete_query = "DELETE FROM isp WHERE isp_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $isp_id);

    if ($stmt->execute()) {
        // If the photo exists in the DB and the file exists on the server, delete the file
        if ($photo_row && !empty($photo_row['isp_photo']) && file_exists($photo_row['isp_photo'])) {
            unlink($photo_row['isp_photo']); // Remove the photo from the server
        }

        echo json_encode(["success" => true, "message" => "ISP deleted successfully, along with the photo."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete ISP: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}