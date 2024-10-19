<?php
session_start();
header('Content-Type: application/json');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please log in as an admin."]);
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

// Prepare and execute the SQL statement to get admin details
$stmt = $conn->prepare("SELECT admin_id, admin_full_name, admin_email, admin_phone_number, admin_created_at FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc(); // Fetch admin details

    // Send admin data as JSON response
    echo json_encode([
        "success" => true,
        "data" => [
            "admin_id" => $admin['admin_id'],
            "admin_full_name" => $admin['admin_full_name'],
            "admin_email" => $admin['admin_email'],
            "admin_phone_number" => $admin['admin_phone_number'],
            "admin_created_at" => $admin['admin_created_at']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Admin not found."]);
}

$stmt->close();
$conn->close();
?>