<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and retrieve form fields
    $isp_id = isset($_POST['isp_id']) ? trim($_POST['isp_id']) : null;
    $promotion_text = isset($_POST['promotion_text']) ? trim($_POST['promotion_text']) : null;

    if ($isp_id && $promotion_text) {
        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO isp_promotion (isp_id, promotion_text) VALUES (?, ?)");
        $stmt->bind_param("is", $isp_id, $promotion_text);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Promotion added successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding promotion: " . $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Please fill out all fields."]);
    }
}

$conn->close();
?>