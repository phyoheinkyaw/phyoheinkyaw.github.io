<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login if not logged in
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Fetch all ISPs from the database
$query = "SELECT isp_id, isp_name, isp_contact_email, isp_contact_phone, isp_photo FROM isp ORDER BY isp_id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Management - ISP Connect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar Content Start -->
        <?php include_once 'admin_include/sidebar.php'; ?>
        <!-- Sidebar Content End -->
        <!-- Main DIV Start -->
        <?php include_once 'admin_include/navigation.php'; ?>

        <!-- Main Content Start -->
        <main class="content">
            <div class="container-fluid">
                <div class="my-3">
                    <h4 id="formTitle">Add New ISP</h4> <!-- This will change dynamically to 'Edit ISP' when editing -->
                </div>

                <!-- Add/Edit ISP Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="ispForm" enctype="multipart/form-data">
                            <input type="hidden" id="ispId" value=""> <!-- Hidden field for ISP ID when editing -->

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="ispName" class="form-label">ISP Name</label>
                                    <input type="text" id="ispName" name="isp_name" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispSlogan" class="form-label">ISP Slogan</label>
                                    <input type="text" id="ispSlogan" name="isp_slogan" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispAvailableSpeeds" class="form-label">Available Speeds</label>
                                    <input type="text" id="ispAvailableSpeeds" name="isp_available_speeds"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispSupportDetails" class="form-label">Support Details</label>
                                    <input type="text" id="ispSupportDetails" name="isp_support_details"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispContractLength" class="form-label">Contract Length</label>
                                    <input type="text" id="ispContractLength" name="isp_contract_length"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispAvailability" class="form-label">Availability</label>
                                    <input type="text" id="ispAvailability" name="isp_availability"
                                        class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispContactEmail" class="form-label">Contact Email</label>
                                    <input type="email" id="ispContactEmail" name="isp_contact_email"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispContactPhone" class="form-label">Contact Phone</label>
                                    <input type="text" id="ispContactPhone" name="isp_contact_phone"
                                        class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispDescription" class="form-label">Description</label>
                                    <textarea id="ispDescription" name="isp_description"
                                        class="form-control"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ispPhoto" class="form-label">ISP Photo</label>
                                    <input type="file" id="ispPhoto" name="isp_photo" class="form-control"
                                        accept="image/*">
                                    <!-- Temporary Photo Preview -->
                                    <img id="ispPhotoPreview" src=""
                                        style="display:none; margin-top: 10px; max-width: 150px;"
                                        alt="ISP Photo Preview">
                                </div>
                            </div>
                            <button type="submit" id="submitButton" class="btn btn-success">Add ISP</button>
                            <button type="button" id="cancelButton" class="btn btn-secondary"
                                style="display: none;">Cancel</button>
                        </form>
                    </div>
                </div>

                <!-- ISP Table -->
                <div class="row">
                    <div class="col-md-12">
                        <table id="ispTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>ISP Name</th>
                                    <!-- <th>Contact Email</th>
                                    <th>Contact Phone</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['isp_id']); ?></td>
                                    <td>
                                        <?php if (!empty($row['isp_photo'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['isp_photo']); ?>" alt="ISP Photo"
                                            style="height:100px;">
                                        <?php else: ?>
                                        <span>No photo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['isp_name']); ?></td>
                                    <!-- <td><?php echo htmlspecialchars($row['isp_contact_email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['isp_contact_phone']); ?></td> -->
                                    <td>
                                        <button class="btn btn-info btn-sm view-isp"
                                            data-id="<?php echo $row['isp_id']; ?>">View</button>
                                        <button class="btn btn-primary btn-sm edit-isp"
                                            data-id="<?php echo $row['isp_id']; ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-isp"
                                            data-id="<?php echo $row['isp_id']; ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5">No ISPs found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <!-- Main Content End -->

        <!-- Footer Start -->
        <?php include_once 'admin_include/footer.php'; ?>
        <!-- Footer End -->

    </div>
    <!-- Main DIV End -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        let isEditing = false; // Track if we are in edit mode

        // Initialize DataTable
        $('#ispTable').DataTable();

        // View ISP
        $('#ispTable').on('click', '.view-isp', function() {
            const ispId = $(this).data('id'); // Get the ISP ID from the button

            // Send AJAX request to fetch ISP details
            $.ajax({
                url: 'get_isp_details.php', // URL of the PHP script to fetch ISP details
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    isp_id: ispId
                }),
                success: function(response) {
                    if (response.success) {
                        const isp = response.data; // ISP data from the server

                        // Display the details in SweetAlert
                        Swal.fire({
                            title: 'ISP Information',
                            html: `
                            <table class="table text-start">
                                <tr><th>ISP Name</th><td>${isp.isp_name}</td></tr>
                                <tr><th>ISP Slogan</th><td>${isp.isp_slogan}</td></tr>
                                <tr><th>Available Speeds</th><td>${isp.isp_available_speeds}</td></tr>
                                <tr><th>Support Details</th><td>${isp.isp_support_details}</td></tr>
                                <tr><th>Contact Email</th><td>${isp.isp_contact_email}</td></tr>
                                <tr><th>Contact Phone</th><td>${isp.isp_contact_phone}</td></tr>
                                <tr><th>Description</th><td>${isp.isp_description}</td></tr>
                                <tr><th>Photo</th><td><img src="${isp.isp_photo}" style="max-height: 150px;" alt="ISP Photo"></td></tr>
                            </table>`,
                            showCloseButton: true,
                            confirmButtonText: 'Close'
                        });
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
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Add/Edit ISP Form Submission
        $('#ispForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const ispId = $('#ispId')
                .val(); // Get ISP ID from hidden field to determine whether to edit

            if (isEditing) {
                formData.append('isp_id', ispId);
                // Update ISP (Edit Mode)
                $.ajax({
                    url: 'edit_isp.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
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
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                // Add new ISP (Add Mode)
                $.ajax({
                    url: 'add_isp.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
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
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        // Edit ISP
        $('#ispTable').on('click', '.edit-isp', function() {
            const ispId = $(this).data('id');
            isEditing = true; // Set to edit mode
            $('#formTitle').text('Edit ISP'); // Change form title
            $('#submitButton').text('Edit ISP'); // Change button text

            $('#cancelButton').show();
            $('#ispName').focus();

            // Fetch ISP details to populate form for editing
            $.ajax({
                url: 'get_isp_details.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    isp_id: ispId
                }),
                success: function(response) {
                    if (response.success) {
                        const isp = response.data;

                        // Populate the form with existing ISP data
                        $('#ispId').val(isp.isp_id); // Hidden field
                        $('#ispName').val(isp.isp_name);
                        $('#ispSlogan').val(isp.isp_slogan);
                        $('#ispAvailableSpeeds').val(isp.isp_available_speeds);
                        $('#ispSupportDetails').val(isp.isp_support_details);
                        $('#ispContractLength').val(isp.isp_contract_length);
                        $('#ispAvailability').val(isp.isp_availability);
                        $('#ispContactEmail').val(isp.isp_contact_email);
                        $('#ispContactPhone').val(isp.isp_contact_phone);
                        $('#ispDescription').val(isp.isp_description);

                        // Show current photo if it exists
                        if (isp.isp_photo) {
                            $('#ispPhotoPreview').attr('src', isp.isp_photo)
                                .show(); // Display photo
                        } else {
                            $('#ispPhotoPreview').hide(); // Hide if no photo
                        }
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
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Cancel button functionality
        $('#cancelButton').on('click', function() {
            // Switch back to Add ISP mode
            isEditing = false;
            $('#formTitle').text('Add New ISP'); // Change form title back
            $('#submitButton').text('Add ISP'); // Change button text back
            $('#cancelButton').hide(); // Hide the Cancel button

            // Clear the form
            $('#ispForm')[0].reset();
            $('#ispId').val(''); // Clear hidden ISP ID
            $('#ispPhotoPreview').hide(); // Hide photo preview
        });

        // Delete ISP
        $('#ispTable').on('click', '.delete-isp', function() {
            const ispId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_isp.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            isp_id: ispId
                        }),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                setTimeout(() => location.reload(), 1600);
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Show an alert with the error details
                            console.log("An error occurred: " + error + "\n" + xhr
                                .responseText);
                        }
                    });
                }
            });
        });

        // Show uploaded image for preview
        $('#ispPhoto').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#ispPhotoPreview').attr('src', e.target.result)
                        .show(); // Display uploaded photo
                }
                reader.readAsDataURL(file);
            } else {
                $('#ispPhotoPreview').hide(); // Hide preview if no file is selected
            }
        });
    });
    </script>
</body>

</html>