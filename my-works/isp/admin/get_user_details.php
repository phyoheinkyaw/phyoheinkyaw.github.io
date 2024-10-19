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

if (!isset($data->user_id)) {
    echo json_encode(["success" => false, "message" => "Invalid request. User ID is required."]);
    exit;
}

$user_id = intval($data->user_id);

// Prepare and execute the SQL statement to get the user details
$stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user details

    // Return user data as JSON
    echo json_encode([
        "success" => true,
        "data" => [
            "user_id" => $user['user_id'],
            "user_full_name" => $user['user_full_name'],
            "user_email" => $user['user_email'],
            "user_phone_number" => $user['user_phone_number'],
            "user_address" => $user['user_address'],
            "user_created_at" => $user['user_created_at']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>