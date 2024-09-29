<?php
require_once 'db_connection.php';

// Create the database if it does not exist
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sqlCreateDB) === TRUE) {
    echo "Database '$database' created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the newly created or existing database
$conn->select_db($database);

// Function to execute a SQL statement and print a success or error message
function executeStatement($conn, $sql, $successMessage) {
    if ($conn->query($sql) === TRUE) {
        echo $successMessage . "<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Drop tables if they exist to avoid conflicts
$tablesToDrop = [
    "ISP_City", "City", "Contact", "Notification", "Review", "Appointment",
    "SubscriptionPlan", "ISP_Promotion", "ISP", "Admin", "User"
];

foreach ($tablesToDrop as $table) {
    $sqlDropTable = "DROP TABLE IF EXISTS $table";
    executeStatement($conn, $sqlDropTable, "Table '$table' dropped successfully if it existed.");
}

// SQL statements for creating tables
$sqlUserTable = "
CREATE TABLE IF NOT EXISTS User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_full_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    user_phone_number VARCHAR(20),
    user_address TEXT,
    user_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sqlAdminTable = "
CREATE TABLE IF NOT EXISTS Admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_full_name VARCHAR(100) NOT NULL,
    admin_email VARCHAR(100) NOT NULL UNIQUE,
    admin_password VARCHAR(255) NOT NULL,
    admin_phone_number VARCHAR(20),
    admin_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sqlISPTable = "
CREATE TABLE IF NOT EXISTS ISP (
    isp_id INT AUTO_INCREMENT PRIMARY KEY,
    isp_name VARCHAR(100) NOT NULL,
    isp_description TEXT,
    isp_slogan VARCHAR(255),
    isp_available_speeds VARCHAR(100),
    isp_support_details VARCHAR(100),
    isp_contract_length VARCHAR(50),
    isp_availability VARCHAR(100),
    isp_contact_email VARCHAR(100),
    isp_contact_phone VARCHAR(20),
    isp_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sqlISPPromotionTable = "
CREATE TABLE IF NOT EXISTS ISP_Promotion (
    promotion_id INT AUTO_INCREMENT PRIMARY KEY,
    isp_id INT NOT NULL,
    promotion_text TEXT NOT NULL,
    promotion_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (isp_id) REFERENCES ISP(isp_id)
)";

$sqlSubscriptionPlanTable = "
CREATE TABLE IF NOT EXISTS SubscriptionPlan (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    isp_id INT NOT NULL,
    plan_name VARCHAR(50) NOT NULL,
    plan_speed VARCHAR(50) NOT NULL,
    plan_price_per_month INT NOT NULL,
    plan_features TEXT,
    plan_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (isp_id) REFERENCES ISP(isp_id)
)";

$sqlAppointmentTable = "
CREATE TABLE IF NOT EXISTS Appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    isp_id INT NOT NULL,
    admin_id INT,
    appointment_date DATE NOT NULL,
    appointment_status TINYINT NOT NULL,
    appointment_address TEXT,
    appointment_payment_screenshot VARCHAR(255),
    appointment_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (isp_id) REFERENCES ISP(isp_id),
    FOREIGN KEY (admin_id) REFERENCES Admin(admin_id)
)";

$sqlReviewTable = "
CREATE TABLE IF NOT EXISTS Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    isp_id INT NOT NULL,
    review_rating TINYINT NOT NULL,
    review_comment TEXT,
    review_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (isp_id) REFERENCES ISP(isp_id)
)";

$sqlNotificationTable = "
CREATE TABLE IF NOT EXISTS Notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_message VARCHAR(255) NOT NULL,
    notification_status TINYINT NOT NULL,
    notification_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
)";

$sqlContactTable = "
CREATE TABLE IF NOT EXISTS Contact (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    contact_user_name VARCHAR(100) NOT NULL,
    contact_email VARCHAR(100) NOT NULL,
    contact_subject VARCHAR(150) NOT NULL,
    contact_message TEXT NOT NULL,
    contact_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$sqlCityTable = "
CREATE TABLE IF NOT EXISTS City (
    city_code CHAR(3) PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL
)";

$sqlISPCityTable = "
CREATE TABLE IF NOT EXISTS ISP_City (
    isp_id INT NOT NULL,
    city_code CHAR(3) NOT NULL,
    PRIMARY KEY (isp_id, city_code),
    FOREIGN KEY (isp_id) REFERENCES ISP(isp_id),
    FOREIGN KEY (city_code) REFERENCES City(city_code)
)";

// Execute the SQL statements to create each table
$createTableStatements = [
    $sqlUserTable => "User table created successfully.",
    $sqlAdminTable => "Admin table created successfully.",
    $sqlISPTable => "ISP table created successfully.",
    $sqlISPPromotionTable => "ISP_Promotion table created successfully.",
    $sqlSubscriptionPlanTable => "SubscriptionPlan table created successfully.",
    $sqlAppointmentTable => "Appointment table created successfully.",
    $sqlReviewTable => "Review table created successfully.",
    $sqlNotificationTable => "Notification table created successfully.",
    $sqlContactTable => "Contact table created successfully.",
    $sqlCityTable => "City table created successfully.",
    $sqlISPCityTable => "ISP_City table created successfully."
];

foreach ($createTableStatements as $sql => $successMessage) {
    executeStatement($conn, $sql, $successMessage);
}

// Close the connection
$conn->close();
?>
