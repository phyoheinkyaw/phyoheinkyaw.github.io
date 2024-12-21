<?php
require_once 'include/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cfmpassword = $_POST['cfmpassword'];

    // Validate form inputs
    if (empty($fullname) || empty($email) || empty($password) || empty($cfmpassword)) {
        die('All fields are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email address.');
    }

    if ($password !== $cfmpassword) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die('Email is already registered.');
    }

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO user (user_full_name, user_email, user_password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Start session and log in the user
        session_start();
        $_SESSION['user_id'] = $stmt->insert_id; // Store user ID in the session
        $_SESSION['user_full_name'] = $fullname;

        // Redirect to index.php with success message
        header('Location: index.php?success=1');
        exit;
    } else {
        die('Registration failed. Please try again.');
    }

    $stmt->close();
    $conn->close();
}
?>