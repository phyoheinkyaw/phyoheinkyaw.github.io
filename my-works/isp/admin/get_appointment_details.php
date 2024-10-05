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

if (!isset($data->appointment_id)) {
    echo json_encode(["success" => false, "message" => "Invalid request. Appointment ID is missing."]);
    exit;
}

$appointment_id = intval($data->appointment_id);

// Fetch the appointment details
$query = "SELECT appointment.*, user.user_full_name, isp.isp_name 
          FROM appointment
          INNER JOIN user ON appointment.user_id = user.user_id
          INNER JOIN isp ON appointment.isp_id = isp.isp_id
          WHERE appointment.appointment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $appointment = $result->fetch_assoc();
    echo json_encode(["success" => true, "data" => $appointment]);
} else {
    echo json_encode(["success" => false, "message" => "Appointment not found."]);
}

$stmt->close();
$conn->close();
?>
