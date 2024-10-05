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
    echo json_encode(["success" => false, "message" => "Invalid request. User ID is missing."]);
    exit;
}

$user_id = intval($data->user_id);

try {
    // Begin transaction
    $conn->begin_transaction();

    // Step 1: Check if the user has any associated appointments
    $stmt_check_appointments = $conn->prepare("SELECT COUNT(*) FROM appointment WHERE user_id = ?");
    $stmt_check_appointments->bind_param("i", $user_id);
    $stmt_check_appointments->execute();
    $stmt_check_appointments->bind_result($appointment_count);
    $stmt_check_appointments->fetch();
    $stmt_check_appointments->close();  // Properly close this statement before proceeding

    // If the user has appointments, notify the admin to delete appointments first
    if ($appointment_count > 0) {
        throw new Exception("This user has " . $appointment_count . " associated appointment(s). Please delete these appointments before deleting the user.");
    }

    // Step 2: If no appointments, proceed to delete the user
    $stmt_user = $conn->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();

    if ($stmt_user->affected_rows > 0) {
        // Commit transaction
        $conn->commit();
        $stmt_user->close();
        echo json_encode(["success" => true, "message" => "User deleted successfully."]);
    } else {
        $stmt_user->close();
        throw new Exception("User not found.");
    }

} catch (Exception $e) {
    // Rollback transaction if there's an error
    if ($conn->errno) {
        $conn->rollback();
    }
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
