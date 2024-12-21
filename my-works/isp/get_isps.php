<?php
require_once 'include/db_connection.php';

// Query to fetch all ISPs
$sql = "SELECT isp_id, isp_name, isp_description, isp_photo, isp_contact_email FROM isp ORDER BY isp_id ASC";
$result = $conn->query($sql);

$response = [];

if ($result->num_rows > 0) {
    // Fetch each ISP as an associative array
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$conn->close();
?>