<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->appointment_id) || !isset($data->appointment_status)) {
    echo json_encode(["success" => false, "message" => "Invalid request. All fields are required."]);
    exit;
}

$appointment_id = intval($data->appointment_id);
$appointment_status = intval($data->appointment_status);
$admin_id = intval($_SESSION['admin_id']);  // Get the admin ID from the session

// Update the appointment status and record the admin who made the change
$stmt = $conn->prepare("UPDATE appointment SET appointment_status = ?, admin_id = ? WHERE appointment_id = ?");
$stmt->bind_param("iii", $appointment_status, $admin_id, $appointment_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Appointment status updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update appointment status: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
