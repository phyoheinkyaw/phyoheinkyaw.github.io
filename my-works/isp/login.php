<?php
session_start();
require_once 'include/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        die('Email and password are required.');
    }

    // Check if the user exists
    $stmt = $conn->prepare("SELECT user_id, user_full_name, user_password FROM user WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['user_password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_full_name'] = $user['user_full_name'];

            // Redirect to index.php with success message
            header('Location: index.php?login=success');
            exit;
        } else {
            // Invalid password
            header('Location: login.html?error=invalid_password');
            exit;
        }
    } else {
        // User not found
        header('Location: login.html?error=user_not_found');
        exit;
    }

    $stmt->close();
}

$conn->close();
?>