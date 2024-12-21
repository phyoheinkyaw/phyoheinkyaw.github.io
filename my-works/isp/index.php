<?php
session_start();
require_once 'include/db_connection.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userData = [];

if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }

    $stmt->close();
}

// Fetch featured ISPs from the database
$query = "SELECT isp_id, isp_name, isp_slogan, isp_photo, isp_description FROM isp WHERE is_featured = 1";
$result = $conn->query($query);
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
                            <a href="index.php" class="nav-link active">Home</a>
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
                        <li class="nav-item">
                            <a href="login.html" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="register.html" class="nav-link">Register</a>
                        </li>
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

    <!-- Hero Start -->
    <section class="hero bg-primary text-light text-center py-5">
        <div class="container">
            <h1>Find The Best ISP In Your Area</h1>
            <p>Compare, Select and Book appointments with the top Internet Service Provider.</p>
        </div>
    </section>
    <!-- Hero End -->

    <!-- Featured ISP Start -->
    <section class="featured-isp py-3">
        <div class="container">
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success text-center">Registration successful! Welcome to ISP Connect.</div>
            <?php endif; ?>
            <?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>
            <div class="alert alert-success text-center">You have successfully logged in. Welcome back!</div>
            <?php endif; ?>
            <h4>Featured ISPs</h4>
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="assets/img/isp/<?php echo htmlspecialchars($row['isp_photo']);?>"
                                    class="card-img" alt="<?php echo htmlspecialchars($row['isp_name']); ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['isp_name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($row['isp_description']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-2 text-center align-self-center">
                                <button class="btn btn-primary view-details" data-id="<?php echo $row['isp_id']; ?>"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="View Details of <?php echo htmlspecialchars($row['isp_name']);?>"><i
                                        class="fa-solid fa-circle-info"></i></button>
                                <button class="btn btn-success bookAppointmentButton"
                                    data-id="<?php echo $row['isp_id']; ?>"><i
                                        class="fa-solid fa-calendar-check"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No featured ISPs found.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </section>
    <!-- Featured ISP End -->

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
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

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