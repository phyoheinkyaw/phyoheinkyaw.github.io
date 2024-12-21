<?php
session_start();
require_once 'include/db_connection.php';

// Check if ISP ID is provided in the query string
if (!isset($_GET['isp_id'])) {
    header('Location: all-isp.html');
    exit;
}

$ispId = intval($_GET['isp_id']);
$isLoggedIn = isset($_SESSION['user_id']);
$userData = [];

// Fetch user details if logged in
if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }

    $stmt->close();
}

// Fetch ISP details
$stmt = $conn->prepare("SELECT * FROM isp WHERE isp_id = ?");
$stmt->bind_param("i", $ispId);
$stmt->execute();
$ispDetails = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ispDetails) {
    header('Location: all-isp.html');
    exit;
}

// Fetch ISP promotions
$stmt = $conn->prepare("SELECT promotion_text FROM isp_promotion WHERE isp_id = ?");
$stmt->bind_param("i", $ispId);
$stmt->execute();
$promotionsResult = $stmt->get_result();
$promotions = [];
while ($row = $promotionsResult->fetch_assoc()) {
    $promotions[] = $row['promotion_text'];
}
$stmt->close();

// Fetch subscription plans
$stmt = $conn->prepare("SELECT plan_name, plan_speed, plan_price_per_month, plan_features FROM subscription_plan WHERE isp_id = ?");
$stmt->bind_param("i", $ispId);
$stmt->execute();
$pricingPlansResult = $stmt->get_result();
$pricingPlans = [];
while ($row = $pricingPlansResult->fetch_assoc()) {
    $pricingPlans[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ISP Connect</title>

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
                            <a href="all-isp.html" class="nav-link">All ISP</a>
                        </li>
                        <li class="nav-item">
                            <a href="about.html" class="nav-link">About</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact.html" class="nav-link">Contact</a>
                        </li>
                        <?php if (!$isLoggedIn): ?>
                        <li class="nav-item"><a href="login.html" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="register.html" class="nav-link">Register</a></li>
                        <?php else: ?>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="profileDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i> Profile
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li><a href="profile.php" class="dropdown-item">View Profile</a></li>
                                <li><a href="logout.php" class="dropdown-item">Logout</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Header End -->

    <!-- ISP Details Section -->
    <section class="isp-details-section py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img id="ispPhoto" src="assets/img/isp/<?php echo htmlspecialchars($ispDetails['isp_photo']); ?>"
                        alt="<?php echo htmlspecialchars($ispDetails['isp_name']); ?>" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h2 id="ispName"><?php echo htmlspecialchars($ispDetails['isp_name']); ?></h2>
                    <p id="ispSlogan" class="lead"><?php echo htmlspecialchars($ispDetails['isp_slogan']); ?></p>
                    <p id="ispDescription"><?php echo htmlspecialchars($ispDetails['isp_description']); ?></p>
                    <ul id="ispDetailsList" class="list-unstyled details-list">
                        <li><span class="details-label">Available Speeds:</span>
                            <?php echo htmlspecialchars($ispDetails['isp_available_speeds'] ?? 'N/A'); ?></li>
                        <li><span class="details-label">Customer Support:</span>
                            <?php echo htmlspecialchars($ispDetails['isp_support_details'] ?? 'N/A'); ?></li>
                        <li><span class="details-label">Contract Length:</span>
                            <?php echo htmlspecialchars($ispDetails['isp_contract_length'] ?? 'N/A'); ?></li>
                        <li><span class="details-label">Availability:</span>
                            <?php echo htmlspecialchars($ispDetails['isp_availability'] ?? 'N/A'); ?></li>
                    </ul>
                    <div>
                        <button class="btn btn-success bookAppointmentButton">
                            <i class="fa-solid fa-calendar-check"></i> Book Appointment
                        </button>
                        <a id="ispContactButton"
                            href="mailto:<?php echo htmlspecialchars($ispDetails['isp_contact_email']); ?>"
                            class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Send Message">
                            <i class="fa-solid fa-envelope"></i> Contact ISP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- More Details Start -->
    <section class="more-isp-details-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>More Information</h3>
                    <div class="promotions mt-3">
                        <h4>Current Promotions</h4>
                        <ul id="promotionsList">
                            <?php if (count($promotions) > 0): ?>
                            <?php foreach ($promotions as $promotion): ?>
                            <li><?php echo htmlspecialchars($promotion); ?></li>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <li>No promotions available.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="pricing">
                        <h4>Pricing Plan</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>Speed</th>
                                    <th>Price per month</th>
                                    <th>Features</th>
                                </tr>
                            </thead>
                            <tbody id="pricingTable">
                                <?php if (count($pricingPlans) > 0): ?>
                                <?php foreach ($pricingPlans as $plan): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($plan['plan_name']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['plan_speed']); ?></td>
                                    <td>$<?php echo htmlspecialchars($plan['plan_price_per_month']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['plan_features']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4">No pricing plans available.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- More Details End -->

    <!-- Customer Review Start -->
    <section class="cutomer-review-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-3">Customer Reviews</h3>
                    <div class="media mb-2">
                        <img src="assets/img/male.png" alt="Profile Picture" class="rounded-circle">
                        <div class="media-body">
                            <h5>Jhon Doe</h5>
                            <div class="mb-2">
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-regular fa-star text-warning"></i>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat.</p>
                        </div>
                    </div>

                    <div class="media mb-2">
                        <img src="assets/img/female.png" alt="Profile Picture" class="rounded-circle">
                        <div class="media-body">
                            <h5>Alice Jhonson</h5>
                            <div class="mb-2">
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-solid fa-star text-warning"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Customer Review End -->

    <!-- Login Required Modal -->
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginRequiredModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <p class="text-center">You need to log in to book an appointment.</p>
                            <form action="login.php" method="POST">
                                <div class="form-group mb-3">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Enter Your Email Address" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Enter Your Password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                            <!-- <div class="text-center">
                                <a href="">Forgot Password?</a>
                            </div> -->
                            <div class="text-center mt-4">
                                <p>Don't have an account? <a href="register.html">Register</a> Now.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Booking Modal Start -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book An Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" id="modal_appointmentForm">
                        <input type="hidden" name="user_id" id="modal_userId"
                            value="<?php echo $isLoggedIn ? $_SESSION['user_id'] : ''; ?>">
                        <input type="hidden" name="isp_id" id="modal_ispId" value="">

                        <div class="form-group mb-3">
                            <label for="modal_name">Name</label>
                            <input type="text" name="name" id="modal_name" class="form-control"
                                placeholder="Enter Your Name"
                                value="<?php echo $isLoggedIn ? htmlspecialchars($userData['user_full_name']) : ''; ?>"
                                readonly required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_email">Email</label>
                            <input type="email" name="email" id="modal_email" class="form-control"
                                placeholder="Enter Your Email"
                                value="<?php echo $isLoggedIn ? htmlspecialchars($userData['user_email']) : ''; ?>"
                                readonly required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_phone">Phone Number</label>
                            <input type="text" name="phone" id="modal_phone" class="form-control"
                                placeholder="Enter Your Phone Number"
                                value="<?php echo $isLoggedIn ? htmlspecialchars($userData['user_phone_number'] ?? '') : ''; ?>"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_address">Address</label>
                            <textarea name="address" id="modal_address" class="form-control" rows="3"
                                placeholder="Enter Your Address"
                                required><?php echo $isLoggedIn ? htmlspecialchars($userData['user_address'] ?? '') : ''; ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_appointmentDate">Appointment Date</label>
                            <input type="date" name="appointmentDate" id="modal_appointmentDate" class="form-control"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="modal_fileUpload">Payment Screenshot</label>
                            <input type="file" name="fileUpload" id="modal_fileUpload" class="form-control"
                                accept="image/*" required>
                            <img id="modal_imagePreview"
                                style="display:none; margin-top:10px; max-width:100%; height:auto;" alt="Preview">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modal_appointmentForm">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointment Booking Modal End -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/script.js"></script>
    <script>
    $(document).ready(function() {
        // Handle Book Appointment Button
        $(document).on('click', '.bookAppointmentButton', function() {
            const ispId = $(this).data('id');
            const isLoggedIn = < ? php echo json_encode($isLoggedIn); ? > ;

            if (!isLoggedIn) {
                $('#loginRequiredModal').modal('show');
            } else {
                $('#modal_ispId').val(ispId); // Set ISP ID in the hidden field
                $('#appointmentModal').modal('show');
            }
        });

        // Reset appointment modal fields when the modal is closed
        $('#appointmentModal').on('hidden.bs.modal', function() {
            const form = $('#modal_appointmentForm')[0]; // Get the appointment form element
            form.reset(); // Reset all form fields
            $('#modal_imagePreview').hide(); // Hide the image preview
        });

        // Preview Uploaded Image
        $('#modal_fileUpload').on('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            const imagePreview = $('#modal_imagePreview'); // Get the image preview element

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.attr('src', e.target.result).show(); // Set the image source
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            } else {
                imagePreview.hide(); // Hide the preview if no file is selected
            }
        });

        // Handle Appointment Form Submission
        $('#modal_appointmentForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: 'submit_appointment.php',
                method: 'POST',
                data: formData,
                processData: false, // Do not process data
                contentType: false, // Do not set content-type header
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#appointmentModal').modal('hide');
                        $('#modal_appointmentForm')[0].reset(); // Reset form
                        $('#modal_imagePreview').hide(); // Hide image preview
                        setTimeout(() => location.reload(), 1600);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while booking the appointment. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>