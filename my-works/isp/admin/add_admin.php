<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] != 1) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Only super admins can add new admins."]);
    exit;
}

require_once '../include/db_connection.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->admin_full_name) || !isset($data->admin_email) || !isset($data->admin_phone_number) || !isset($data->admin_password)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

$admin_full_name = trim($data->admin_full_name);
$admin_email = trim($data->admin_email);
$admin_phone_number = trim($data->admin_phone_number);
$admin_password = password_hash(trim($data->admin_password), PASSWORD_DEFAULT);  // Hash the password

// Check if the email already exists
$query = "SELECT * FROM admin WHERE admin_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Admin with this email already exists."]);
    exit;
}

// Insert the new admin into the database
$stmt = $conn->prepare("INSERT INTO admin (admin_full_name, admin_email, admin_phone_number, admin_password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $admin_full_name, $admin_email, $admin_phone_number, $admin_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Admin added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add admin: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>