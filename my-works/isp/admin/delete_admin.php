<?php
session_start();
header('Content-Type: application/json');

// Only allow super admin to delete admins
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Only the super admin can delete admins."]);
    exit;
}

require_once '../include/db_connection.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->admin_id)) {
    echo json_encode(["success" => false, "message" => "Invalid request. Admin ID is required."]);
    exit;
}

$admin_id = intval($data->admin_id);

// Ensure the super admin doesn't delete themselves
if ($admin_id == $_SESSION['admin_id']) {
    echo json_encode(["success" => false, "message" => "You cannot delete yourself."]);
    exit;
}

// Prepare and execute the SQL query to delete the admin
$stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Admin deleted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete admin: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>