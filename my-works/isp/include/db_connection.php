<?php
// Database Connection Variables
$servername = "localhost"; //127.0.0.1
$username = "root";
$password = "";
$database = "isp_connect";

// Create Connection to MySQL server
$conn = new mysqli($servername,$username,$password,$database);

// Check connection to MySQL Server
if($conn->connect_error){
	die("Connection Failed: " . $conn->connect_error);
}
?>