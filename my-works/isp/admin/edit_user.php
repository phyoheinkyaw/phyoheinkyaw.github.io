<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please log in as an admin."]);
    exit;
}

require_once '../include/db_connection.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || !isset($data->user_full_name) || !isset($data->user_email) || !isset($data->user_phone_number) || !isset($data->user_address)) {
    echo json_encode(["success" => false, "message" => "Invalid request. All fields are required."]);
    exit;
}

$user_id = intval($data->user_id);
$user_full_name = trim($data->user_full_name);
$user_email = trim($data->user_email);
$user_phone_number = trim($data->user_phone_number);
$user_address = trim($data->user_address);  // New field for the address

// Prepare and execute the SQL statement to update the user, including the address
$stmt = $conn->prepare("UPDATE user SET user_full_name = ?, user_email = ?, user_phone_number = ?, user_address = ? WHERE user_id = ?");
$stmt->bind_param("ssssi", $user_full_name, $user_email, $user_phone_number, $user_address, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update user: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>