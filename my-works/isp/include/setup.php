<?php
require_once 'db_connection.php';

// Create the database if it does not exist
// $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $database";

// if($conn->query($sqlCreateDB) === TRUE){
// 	echo "Database $database created successfully or already exists. <br/>";
// }else{
// 	die("Error creating database: " . $conn->error);
// }

// Select the database isp_connect
// $conn->select_db($database);

// Drop tables if they exist
$tablesDrop = ["isp_city","city","contact","notification","appointment","review","subscription_plan","isp_promotion","isp","user","admin"];

forEach($tablesDrop as $table){
	$sqlDropTable = "DROP TABLE IF EXISTS $table";
	executeStatement($conn, $sqlDropTable, "Table $table dropped successfully.");
}


// SQL statements for tables
$sqlAdminTable = "
CREATE TABLE IF NOT EXISTS admin(
	admin_id int auto_increment primary key,
	admin_full_name varchar(100) not null,
	admin_email varchar(100) not null unique,
	admin_password varchar(255) not null,
	admin_phone_number varchar(20),
	admin_created_at timestamp default current_timestamp
)";

$sqlUserTable = "
CREATE TABLE IF NOT EXISTS user(
	user_id int auto_increment primary key,
	user_full_name varchar(100) not null,
	user_email varchar(100) not null unique,
	user_password varchar(255) not null,
	user_phone_number varchar(20),
	user_address text,
	user_created_at timestamp default current_timestamp
)";


$sqlISPTable = "
CREATE TABLE IF NOT EXISTS isp(
	isp_id int auto_increment primary key,
	isp_name varchar(100) not null,
	isp_slogan varchar(255),
	isp_description text,
	isp_available_speeds varchar(100),
	isp_support_details varchar(100),
	isp_contract_length varchar(50),
	isp_availability varchar(100),
	isp_contact_email varchar(100),
	isp_contact_phone varchar(20),
	isp_photo varchar(255),
	isp_created_at timestamp default current_timestamp
)";

$sqlISPPromotionTable = "
CREATE TABLE IF NOT EXISTS isp_promotion(
	promotion_id int auto_increment primary key,
	isp_id int not null,
	promotion_text text not null,
	promotion_created_at timestamp default current_timestamp,
	foreign key(isp_id) references isp(isp_id)
)";

$sqlSubscriptionPlanTable = "
CREATE TABLE IF NOT EXISTS subscription_plan(
	plan_id int auto_increment primary key,
	isp_id int not null,
	plan_name varchar(50) not null,
	plan_speed varchar(50) not null,
	plan_price_per_month int not null,
	plan_features text,
	plan_created_at timestamp default current_timestamp,
	foreign key(isp_id) references isp(isp_id)
)";

$sqlReviewTable = "
CREATE TABLE IF NOT EXISTS review(
	review_id int auto_increment primary key,
	user_id int not null,
	isp_id int not null,
	review_rating tinyint not null,
	review_comment text,
	review_created_at timestamp default current_timestamp,
	foreign key(user_id) references user(user_id),
	foreign key(isp_id) references isp(isp_id)
)";

$sqlAppointmentTable = "
CREATE TABLE IF NOT EXISTS appointment(
	appointment_id int auto_increment primary key,
	user_id int not null,
	isp_id int not null,
	admin_id int not null,
	appointment_phone_number varchar(20) not null,
	appointment_address text not null,
	appointment_date date not null,
	appointment_status tinyint not null,
	appointment_payment_screenshot varchar(255) not null,
	appointment_created_at timestamp default current_timestamp,
	foreign key(user_id) references user(user_id),
	foreign key(isp_id) references isp(isp_id),
	foreign key(admin_id) references admin(admin_id)
)";

$sqlNotificationTable = "
CREATE TABLE IF NOT EXISTS notification(
	notification_id int auto_increment primary key,
	user_id int not null,
	notification_message varchar(255) not null,
	notification_status tinyint not null,
	notification_created_at timestamp default current_timestamp,
	foreign key(user_id) references user(user_id)
)";

$sqlContactTable = "
CREATE TABLE IF NOT EXISTS contact(
	contact_id int auto_increment primary key,
	contact_name varchar(100) not null,
	contact_email varchar(100) not null,
	contact_subject varchar(150) not null,
	contact_message text not null,
	contact_created_at timestamp default current_timestamp
)";

$sqlCityTable = "
CREATE TABLE IF NOT EXISTS city(
	city_code char(3) primary key,
	city_name varchar(100) not null
)";

$sqlISPCityTable = "
CREATE TABLE IF NOT EXISTS isp_city(
	isp_id int not null,
	city_code char(3) not null,
	primary key(isp_id, city_code),
	foreign key(isp_id) references isp(isp_id),
	foreign key(city_code) references city(city_code)
)";

// Execute the SQL statements to create each table
$createTableStatement = [
	$sqlAdminTable => "Admin Table created successfully",
	$sqlUserTable => "User Table created successfully",
	$sqlISPTable => "ISP Table created successfully",
	$sqlISPPromotionTable => "ISP Promotion Table created successfully",
	$sqlSubscriptionPlanTable => "Subscription Plan Table created successfully",
	$sqlReviewTable => "Review Table created successfully",
	$sqlAppointmentTable => "Appointment Table created successfully",
	$sqlNotificationTable => "Notification Table created successfully",
	$sqlContactTable => "Contact Table created successfully",
	$sqlCityTable => "City Table created successfully",
	$sqlISPCityTable => "ISP City Table created successfully"
];

// Function to execute each sql statement
function executeStatement($conn, $sql, $successMessage){
	if($conn->query($sql) === TRUE){
		echo $successMessage . "<br/>";
	}else{
		echo "Error: " . $conn->error . "<br/>";
	}
}

forEach($createTableStatement as $sql => $successMessage){
	executeStatement($conn, $sql, $successMessage);
}

// Close the connection
$conn->close();
?>