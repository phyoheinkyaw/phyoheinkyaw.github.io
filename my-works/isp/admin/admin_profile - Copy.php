<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT admin_full_name, admin_email, admin_phone_number FROM admin WHERE admin_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle profile update submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_full_name = $_POST['admin_full_name'];
    $admin_phone_number = $_POST['admin_phone_number'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Prepared statement to fetch current password hash
    $stmt = $conn->prepare("SELECT admin_password FROM admin WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin_data = $result->fetch_assoc();
    
    if ($result->num_rows > 0 && password_verify($current_password, $admin_data['admin_password'])) {
        // If updating password, check if new passwords match
        if (!empty($new_password) && $new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE admin SET admin_full_name = ?, admin_phone_number = ?, admin_password = ? WHERE admin_id = ?");
            $update_stmt->bind_param("sssi", $admin_full_name, $admin_phone_number, $hashed_password, $admin_id);
        } else {
            // If no password change or passwords don't match, only update full name and phone number
            if (!empty($new_password) && $new_password !== $confirm_password) {
                $error_message = "New password and confirm password do not match.";
            } else {
                $update_stmt = $conn->prepare("UPDATE admin SET admin_full_name = ?, admin_phone_number = ? WHERE admin_id = ?");
                $update_stmt->bind_param("ssi", $admin_full_name, $admin_phone_number, $admin_id);
            }
        }

        if (!isset($error_message) && $update_stmt->execute()) {
            $success_message = "Profile updated successfully.";
            $_SESSION['admin_full_name'] = $admin_full_name; // Update session variable if name changes
        } else {
            $error_message = isset($error_message) ? $error_message : "Failed to update profile. Please try again.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - ISP Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Sidebar Content Start -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="">ISP Connect</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">Admin Elements</li>
                    <li class="sidebar-item">
                        <a href="index.php" class="sidebar-link"><i class="fa-solid fa-list"></i> Dashboard</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="admin_profile.php" class="sidebar-link"><i class="fa-solid fa-user"></i> Profile</a>
                    </li>
                </ul>
            </div>
            <!-- Sidebar Content End -->
        </aside>

        <!-- Main DIV Start -->
        <div class="main">
            <nav class="navbar navbar-expand border-bottom px-3">
                <button type="button" class="btn" id="sidebar-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="" data-bs-toggle="dropdown" class="nav-icon">
                                <img src="img/male.png" class="avatar img-fluid" alt="Admin Profile Picture" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="admin_profile.php" class="dropdown-item">Profile</a>
                                <a href="#" class="dropdown-item">Setting</a>
                                <a href="logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Start -->
            <main class="content">
                <div class="container-fluid">
                    <div class="my-3">
                        <h4>Admin Profile</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php elseif (!empty($error_message)): ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>

                            <form method="POST" action="admin_profile.php">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($admin['admin_email']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="admin_full_name" name="admin_full_name" value="<?php echo htmlspecialchars($admin['admin_full_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="admin_phone_number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="admin_phone_number" name="admin_phone_number" value="<?php echo htmlspecialchars($admin['admin_phone_number']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Main Content End -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 text-start">
                            <p>ISP Connect</p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="#" class="footer-facebook"><i class="fa-brands fa-square-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="footer-instagram"><i class="fa-brands fa-square-instagram"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="footer-telegram"><i class="fa-brands fa-telegram"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->

        </div>
        <!-- Main DIV End -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>
</body>
</html>
