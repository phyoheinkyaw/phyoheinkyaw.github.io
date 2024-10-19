<?php
require_once 'db_connection.php';

// Select the database
$conn->select_db($database);

// Function to execute SQL statements and print success/error messages
function executeStatement($conn, $sql, $successMessage) {
    if ($conn->query($sql) === TRUE) {
        echo $successMessage . "<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Insert sample data into city table
$sqlInsertCities = "
INSERT INTO city (city_code, city_name) VALUES
('YGN', 'Yangon'),
('MDY', 'Mandalay'),
('NPT', 'Naypyidaw')
";
executeStatement($conn, $sqlInsertCities, "Sample cities in Myanmar inserted successfully.");

// Insert sample data into isp table
$sqlInsertISPs = "
INSERT INTO isp (isp_name, isp_description, isp_slogan, isp_available_speeds, isp_support_details, isp_contract_length, isp_availability, isp_contact_email, isp_contact_phone)
VALUES
('Myanmar Net', 'Leading internet service provider in Myanmar.', 'Connecting Myanmar to the world.', '5Mbps, 10Mbps, 20Mbps', '24/7 customer support in Burmese', '12 months', 'Nationwide', 'support@myanmarnet.com.mm', '09-123456789'),
('Ooredoo', 'Reliable mobile and broadband services.', 'Enjoy the internet.', 'Up to 100Mbps', 'Weekdays 9am-6pm', 'No contract', 'Major cities', 'contact@ooredoo.com.mm', '09-987654321')
";
executeStatement($conn, $sqlInsertISPs, "Sample ISPs in Myanmar inserted successfully.");

// Insert sample data into isp_city table
$sqlInsertISPCities = "
INSERT INTO isp_city (isp_id, city_code) VALUES
(1, 'YGN'),
(1, 'MDY'),
(2, 'YGN'),
(2, 'NPT')
";
executeStatement($conn, $sqlInsertISPCities, "Sample ISP-City relationships inserted successfully.");

// Insert sample data into subscription_plan table
$sqlInsertSubscriptionPlans = "
INSERT INTO subscription_plan (isp_id, plan_name, plan_speed, plan_price_per_month, plan_features)
VALUES
(1, 'Basic Plan', '5Mbps', 20000, 'Unlimited data, Free installation'),
(1, 'Premium Plan', '10Mbps', 35000, 'Unlimited data, Free router, Priority support'),
(2, 'Starter Pack', 'Up to 20Mbps', 25000, 'Limited data, Mobile hotspot')
";
executeStatement($conn, $sqlInsertSubscriptionPlans, "Sample subscription plans inserted successfully.");

// Hash the admin password
$adminPassword = 'a';
$hashedAdminPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

// Insert sample data into admin table
$sqlInsertAdmin = "
INSERT INTO admin (admin_full_name, admin_email, admin_password, admin_phone_number)
VALUES
('Admin User', 'admin@admin.com', '$hashedAdminPassword', '09-11112222')
";
executeStatement($conn, $sqlInsertAdmin, "Admin user inserted successfully.");

// Hash user passwords
$user1Password = 'password123';
$hashedUser1Password = password_hash($user1Password, PASSWORD_DEFAULT);

$user2Password = 'mypassword';
$hashedUser2Password = password_hash($user2Password, PASSWORD_DEFAULT);

// Insert sample data into user table
$sqlInsertUsers = "
INSERT INTO user (user_full_name, user_email, user_password, user_phone_number, user_address)
VALUES
('Aung Aung', 'aungaung@example.com.mm', '$hashedUser1Password', '09-22223333', 'No.12, Main St, Yangon'),
('Mya Mya', 'myamya@example.com.mm', '$hashedUser2Password', '09-33334444', 'No.34, Side St, Mandalay')
";
executeStatement($conn, $sqlInsertUsers, "Sample users in Myanmar inserted successfully.");

// Insert sample data into review table
$sqlInsertReviews = "
INSERT INTO review (user_id, isp_id, review_rating, review_comment)
VALUES
(1, 1, 5, 'Excellent service and speed!'),
(2, 2, 4, 'Good coverage in my area.')
";
executeStatement($conn, $sqlInsertReviews, "Sample reviews inserted successfully.");

// Insert sample data into appointment table
$sqlInsertAppointments = "
INSERT INTO appointment (user_id, isp_id, admin_id, appointment_phone_number, appointment_address, appointment_date, appointment_status, appointment_payment_screenshot)
VALUES
(1, 1, 1, '09-22223333', 'No.123, Pyay Road, Yangon', '2023-10-15', 1, 'payment.jpeg'),
(2, 2, 1, '09-33334444', 'No.456, Mandalay-Lashio Road, Mandalay', '2023-10-20', 0, 'payment.jpeg')
";
executeStatement($conn, $sqlInsertAppointments, "Sample appointments inserted successfully.");

// Insert sample data into isp_promotion table
$sqlInsertISPPromotions = "
INSERT INTO isp_promotion (isp_id, promotion_text)
VALUES
(1, 'Get double data for the first 3 months!'),
(2, 'Free SIM card with every new subscription.')
";
executeStatement($conn, $sqlInsertISPPromotions, "Sample ISP promotions inserted successfully.");

// Insert sample data into notification table
$sqlInsertNotifications = "
INSERT INTO notification (user_id, notification_message, notification_status)
VALUES
(1, 'Your installation is scheduled for 2023-10-15.', 0),
(2, 'Please upload your payment receipt to confirm your appointment.', 0)
";
executeStatement($conn, $sqlInsertNotifications, "Sample notifications inserted successfully.");

// Insert sample data into contact table
$sqlInsertContacts = "
INSERT INTO contact (contact_name, contact_email, contact_subject, contact_message)
VALUES
('Kyaw Kyaw', 'kyawkyaw@example.com.mm', 'Inquiry about coverage', 'Is your service available in Naypyidaw?'),
('Su Su', 'susu@example.com.mm', 'Technical support needed', 'I am experiencing slow internet speeds.')
";
executeStatement($conn, $sqlInsertContacts, "Sample contacts inserted successfully.");

// Close the connection
$conn->close();
?>