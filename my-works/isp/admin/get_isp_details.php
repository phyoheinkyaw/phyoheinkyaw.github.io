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

if (!isset($data->isp_id)) {
    echo json_encode(["success" => false, "message" => "Invalid request. ISP ID is required."]);
    exit;
}

$isp_id = intval($data->isp_id);

// Prepare and execute the SQL query to get the ISP details
$stmt = $conn->prepare("SELECT * FROM isp WHERE isp_id = ?");
$stmt->bind_param("i", $isp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $isp = $result->fetch_assoc(); // Fetch the ISP details

    echo json_encode([
        "success" => true,
        "data" => $isp
    ]);
} else {
    echo json_encode(["success" => false, "message" => "ISP not found."]);
}

$stmt->close();
$conn->close();
?>