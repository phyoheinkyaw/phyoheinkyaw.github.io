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
    $isp_id = isset($_POST['isp_id']) ? intval($_POST['isp_id']) : null;
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
    if (!$isp_id || !$isp_name || !$isp_contact_email || !$isp_contact_phone) {
        echo json_encode(["success" => false, "message" => "Required fields are missing."]);
        exit;
    }

    // Initialize variables
    $photo_path = "";
    $new_photo_uploaded = false;

    // Check if a new image is uploaded
    if (isset($_FILES['isp_photo']) && $_FILES['isp_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff'];
        $file_mime_type = mime_content_type($_FILES['isp_photo']['tmp_name']);

        if (!in_array($file_mime_type, $allowed_mime_types)) {
            echo json_encode(["success" => false, "message" => "Invalid file type. Only images are allowed."]);
            exit;
        }

        $photo_dir = 'img/isp/';

        // Create a sanitized name for the photo
        $sanitized_name = str_replace(' ', '_', strtolower($isp_name)); 
        $photo_extension = pathinfo($_FILES['isp_photo']['name'], PATHINFO_EXTENSION);
        $photo_name = $sanitized_name . '.' . $photo_extension;

        $photo_path = $photo_dir . $photo_name;

        // Create the directory if it doesn't exist
        if (!is_dir($photo_dir) && !mkdir($photo_dir, 0755, true)) {
            echo json_encode(["success" => false, "message" => "Failed to create directory for photo upload."]);
            exit;
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['isp_photo']['tmp_name'], $photo_path)) {
            echo json_encode(["success" => false, "message" => "Failed to upload the photo."]);
            exit;
        }

        // Mark that a new photo has been uploaded
        $new_photo_uploaded = true;
    }

    // If a new photo is uploaded, get the old photo to delete
    if ($new_photo_uploaded) {
        $query = "SELECT isp_photo FROM isp WHERE isp_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $isp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $isp = $result->fetch_assoc();

        if ($isp && file_exists($isp['isp_photo'])) {
            unlink($isp['isp_photo']); // Delete the old photo from the server
        }

        // Update the ISP with the new photo path
        $stmt = $conn->prepare("UPDATE isp SET isp_name = ?, isp_slogan = ?, isp_description = ?, isp_available_speeds = ?, isp_support_details = ?, isp_contract_length = ?, isp_availability = ?, isp_contact_email = ?, isp_contact_phone = ?, isp_photo = ? WHERE isp_id = ?");
        $stmt->bind_param("ssssssssssi", $isp_name, $isp_slogan, $isp_description, $isp_available_speeds, $isp_support_details, $isp_contract_length, $isp_availability, $isp_contact_email, $isp_contact_phone, $photo_path, $isp_id);
    } else {
        // Update the ISP without changing the photo
        $stmt = $conn->prepare("UPDATE isp SET isp_name = ?, isp_slogan = ?, isp_description = ?, isp_available_speeds = ?, isp_support_details = ?, isp_contract_length = ?, isp_availability = ?, isp_contact_email = ?, isp_contact_phone = ? WHERE isp_id = ?");
        $stmt->bind_param("sssssssssi", $isp_name, $isp_slogan, $isp_description, $isp_available_speeds, $isp_support_details, $isp_contract_length, $isp_availability, $isp_contact_email, $isp_contact_phone, $isp_id);
    }

    // Execute the query and respond accordingly
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "ISP updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update ISP: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}