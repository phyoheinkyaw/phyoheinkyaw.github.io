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
    $isp_name = isset($_POST['isp_name']) ? trim($_POST['isp_name']) : null;
    $isp_slogan = isset($_POST['isp_slogan']) ? trim($_POST['isp_slogan']) : null;
    $isp_description = isset($_POST['isp_description']) ? trim($_POST['isp_description']) : null;
    $isp_available_speeds = isset($_POST['isp_available_speeds']) ? trim($_POST['isp_available_speeds']) : null;
    $isp_support_details = isset($_POST['isp_support_details']) ? trim($_POST['isp_support_details']) : null;
    $isp_contract_length = isset($_POST['isp_contract_length']) ? trim($_POST['isp_contract_length']) : null;
    $isp_availability = isset($_POST['isp_availability']) ? trim($_POST['isp_availability']) : null;
    $isp_contact_email = isset($_POST['isp_contact_email']) ? trim($_POST['isp_contact_email']) : null;
    $isp_contact_phone = isset($_POST['isp_contact_phone']) ? trim($_POST['isp_contact_phone']) : null;

    // Check if required fields are present
    if (!$isp_name || !$isp_contact_email || !$isp_contact_phone) {
        echo json_encode(["success" => false, "message" => "Required fields are missing."]);
        exit;
    }

    // Handle file upload
    $photo_path = "";
    if (isset($_FILES['isp_photo']) && $_FILES['isp_photo']['error'] === UPLOAD_ERR_OK) {
        // Validate file type (only allow image files)
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff'];
        $file_mime_type = mime_content_type($_FILES['isp_photo']['tmp_name']);
        
        if (!in_array($file_mime_type, $allowed_mime_types)) {
            echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, GIF, WEBP, BMP, TIFF, and SVG files are allowed."]);
            exit;
        }

        $photo_dir = 'img/isp/';
        
        // Create photo name: isp_name with underscores
        $sanitized_name = str_replace(' ', '_', strtolower($isp_name)); // Replace spaces with underscores and lowercase the name

        // Extract the file extension
        $photo_extension = pathinfo($_FILES['isp_photo']['name'], PATHINFO_EXTENSION);

        // Create the new file name with the sanitized ISP name, followed by the file extension
        $photo_name = $sanitized_name . '.' . $photo_extension;

        // Define the file path
        $photo_path = $photo_dir . $photo_name;

        // Check if directory exists, else create it
        if (!is_dir($photo_dir) && !mkdir($photo_dir, 0755, true)) {
            echo json_encode(["success" => false, "message" => "Failed to create directory for photo upload."]);
            exit;
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['isp_photo']['tmp_name'], $photo_path)) {
            echo json_encode(["success" => false, "message" => "Failed to upload the photo."]);
            exit;
        }

        // Store the relative path
        $photo_path = 'img/isp/' . $photo_name;
    }

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO isp (isp_name, isp_slogan, isp_description, isp_available_speeds, isp_support_details, isp_contract_length, isp_availability, isp_contact_email, isp_contact_phone, isp_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssssssssss", $isp_name, $isp_slogan, $isp_description, $isp_available_speeds, $isp_support_details, $isp_contract_length, $isp_availability, $isp_contact_email, $isp_contact_phone, $photo_path);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "ISP added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add ISP: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}