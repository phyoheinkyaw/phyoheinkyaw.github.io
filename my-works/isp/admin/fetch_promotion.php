<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

require_once '../include/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['promotion_id'])) {
    $promotion_id = intval($_GET['promotion_id']);

    $query = "SELECT p.promotion_id, p.promotion_text, p.isp_id, i.isp_name 
              FROM isp_promotion p 
              JOIN isp i ON p.isp_id = i.isp_id 
              WHERE p.promotion_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $promotion_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $promotion = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $promotion]);
    } else {
        echo json_encode(["success" => false, "message" => "Promotion not found."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}