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

// Fetch user details from the database
$stmt = $conn->prepare("SELECT user_full_name, user_email, user_phone_number, user_address, user_password FROM user WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = [];

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    // Redirect if user data is not found
    header("Location: logout.php");
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile | ISP Connect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <!-- Header Start -->
    <header class="bg-dark text-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a href="" class="navbar-brand">
                    <img src="assets/img/logo.png" class="logo" alt="ISP Connect Logo">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle Navigation"><span
                        class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link active">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Header End -->

    <!-- Profile Section Start -->
    <section class="profile-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php
    if ($_GET['error'] == 'invalid_password') {
        echo 'Invalid current password. Please try again.';
    } elseif ($_GET['error'] == 'password_mismatch') {
        echo 'New password and confirm password do not match.';
    } elseif ($_GET['error'] == 'update_failed') {
        echo 'Failed to update profile. Please try again.';
    }
    ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Profile updated successfully!</div>
                <?php endif; ?>
                <div class="col-md-8">
                    <h2 class="text-center mb-4">Your Profile</h2>
                    <form id="profileForm" action="update_profile.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="<?php echo htmlspecialchars($userData['user_email']); ?>" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="fullName">Full Name</label>
                            <input type="text" name="fullName" id="fullName" class="form-control"
                                value="<?php echo htmlspecialchars($userData['user_full_name']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="text" name="phoneNumber" id="phoneNumber" class="form-control"
                                value="<?php echo htmlspecialchars($userData['user_phone_number']); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control"
                                rows="3"><?php echo htmlspecialchars($userData['user_address']); ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" name="currentPassword" id="currentPassword" class="form-control"
                                placeholder="Enter Your Current Password" required>
                        </div>

                        <!-- Accordion for Changing Password -->
                        <div class="accordion mb-3" id="changePasswordAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingChangePassword">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseChangePassword" aria-expanded="false"
                                        aria-controls="collapseChangePassword">
                                        Want to Change Password?
                                    </button>
                                </h2>
                                <div id="collapseChangePassword" class="accordion-collapse collapse"
                                    aria-labelledby="headingChangePassword" data-bs-parent="#changePasswordAccordion">
                                    <div class="accordion-body">
                                        <div class="form-group mb-3">
                                            <label for="newPassword">New Password</label>
                                            <input type="password" name="newPassword" id="newPassword"
                                                class="form-control" placeholder="Enter Your New Password">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="confirmPassword">Confirm Password</label>
                                            <input type="password" name="confirmPassword" id="confirmPassword"
                                                class="form-control" placeholder="Confirm Your New Password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->

    <!-- Footer Start -->
    <footer class="bg-dark text-light py-3">
        <div class="container text-center">
            <p>&copy; 2024 ISP Connect. All Rights Reserved.</p>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="">Privacy Policy</a>
                </li>
                <li class="list-inline-item">
                    <a href="">Terms and Conditions</a>
                </li>
            </ul>
        </div>
    </footer>
    <!-- Footer End -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>