<?php
session_start();
require_once 'include/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id'];

// Get the form data
$fullName = trim($_POST['fullName']);
$phoneNumber = trim($_POST['phoneNumber']);
$address = trim($_POST['address']);
$currentPassword = trim($_POST['currentPassword']);
$newPassword = trim($_POST['newPassword'] ?? '');
$confirmPassword = trim($_POST['confirmPassword'] ?? '');

// Fetch the current user data
$stmt = $conn->prepare("SELECT user_password FROM user WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

// Check if the current password is correct
if (!password_verify($currentPassword, $userData['user_password'])) {
    header("Location: profile.php?error=invalid_password");
    exit;
}

// Validate new password if provided
if (!empty($newPassword) || !empty($confirmPassword)) {
    if ($newPassword !== $confirmPassword) {
        header("Location: profile.php?error=password_mismatch");
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update profile with the new password
    $stmt = $conn->prepare("UPDATE user SET user_full_name = ?, user_phone_number = ?, user_address = ?, user_password = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $fullName, $phoneNumber, $address, $hashedPassword, $userId);
} else {
    // Update profile without changing the password
    $stmt = $conn->prepare("UPDATE user SET user_full_name = ?, user_phone_number = ?, user_address = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $fullName, $phoneNumber, $address, $userId);
}

// Execute the update query
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: profile.php?success=1");
    exit;
} else {
    $stmt->close();
    $conn->close();
    header("Location: profile.php?error=update_failed");
    exit;
}