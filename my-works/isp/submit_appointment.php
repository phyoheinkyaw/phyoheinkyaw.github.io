<?php
session_start();
require_once 'include/db_connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to book an appointment.']);
    exit;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $userId = $_SESSION['user_id'];
    $ispId = isset($_POST['isp_id']) ? intval($_POST['isp_id']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $appointmentDate = isset($_POST['appointmentDate']) ? $_POST['appointmentDate'] : '';
    $fileUpload = $_FILES['fileUpload'] ?? null;

    // Validate inputs
    if (!$ispId || empty($name) || empty($email) || empty($phone) || empty($address) || empty($appointmentDate) || !$fileUpload) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate appointment date (optional: ensure it's not in the past)
    if (strtotime($appointmentDate) < strtotime(date('Y-m-d'))) {
        echo json_encode(['success' => false, 'message' => 'Appointment date cannot be in the past.']);
        exit;
    }

    // Handle file upload
    $uploadDir = 'admin/img/payment/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
        exit;
    }

    $fileName = uniqid() . '.' . pathinfo($fileUpload['name'], PATHINFO_EXTENSION);
    $filePath = $uploadDir . $fileName;

    // Move the uploaded file
    if (!move_uploaded_file($fileUpload['tmp_name'], $filePath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload payment screenshot.']);
        exit;
    }

    // Save the appointment to the database
    $stmt = $conn->prepare("INSERT INTO appointment (user_id, isp_id, appointment_phone_number, appointment_address, appointment_date, appointment_payment_screenshot, appointment_status) VALUES (?, ?, ?, ?, ?, ?, 0)");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("iissss", $userId, $ispId, $phone, $address, $appointmentDate, $fileName);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Appointment booked successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to book the appointment.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();