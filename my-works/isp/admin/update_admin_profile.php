<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

require_once '../include/db_connection.php';

$admin_id = $_SESSION['admin_id'];
$admin_full_name = $_POST['admin_full_name'];
$admin_phone_number = $_POST['admin_phone_number'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Fetch the current password hash
$stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();

if ($result->num_rows > 0 && password_verify($current_password, $admin_data['admin_password'])) {
    // Check if new password and confirm password match, if provided
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE admin SET admin_full_name = ?, admin_phone_number = ?, admin_password = ? WHERE admin_id = ?");
            $update_stmt->bind_param("sssi", $admin_full_name, $admin_phone_number, $hashed_password, $admin_id);
        } else {
            echo json_encode(["success" => false, "message" => "New password and confirm password do not match."]);
            exit;
        }
    } else {
        $update_stmt = $conn->prepare("UPDATE admin SET admin_full_name = ?, admin_phone_number = ? WHERE admin_id = ?");
        $update_stmt->bind_param("ssi", $admin_full_name, $admin_phone_number, $admin_id);
    }

    if ($update_stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update profile. Please try again."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
}

$conn->close();
?>
