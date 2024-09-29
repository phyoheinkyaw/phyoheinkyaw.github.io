<?php
// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$database = "isp_connect";

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection to MySQL server
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>