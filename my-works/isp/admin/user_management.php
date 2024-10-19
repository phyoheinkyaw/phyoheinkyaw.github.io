<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Fetch all users from the database
$query = "SELECT user_id, user_full_name, user_email, user_phone_number, DATE(user_created_at) as user_created_at FROM user";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - ISP Connect</title>
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
                    <h4>User Management</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="userTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Registered Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['user_created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-user"
                                            data-id="<?php echo $row['user_id']; ?>">View</button>
                                        <button class="btn btn-primary btn-sm edit-user"
                                            data-id="<?php echo $row['user_id']; ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-user"
                                            data-id="<?php echo $row['user_id']; ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6">No users found.</td>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#userTable').DataTable();

        // View User
        $('.view-user').on('click', function() {
            const userId = $(this).data('id'); // Get the user ID from the button

            // Send AJAX request to fetch user details
            $.ajax({
                url: 'get_user_details.php', // URL of the PHP script to fetch user details
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    user_id: userId
                }),
                success: function(response) {
                    if (response.success) {
                        const user = response.data; // User data from the server

                        // Display the details in SweetAlert
                        Swal.fire({
                            title: 'User Information',
                            html: `
                    <p><strong>Full Name:</strong> ${user.user_full_name}</p>
                    <p><strong>Email:</strong> ${user.user_email}</p>
                    <p><strong>Phone Number:</strong> ${user.user_phone_number}</p>
                    <p><strong>Address:</strong> ${user.user_address}</p>
                    <p><strong>Registered Date:</strong> ${user.user_created_at}</p>
                    `,
                            showCloseButton: true,
                            showCancelButton: false,
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

        // Edit User using SweetAlert2
        $('.edit-user').on('click', function() {
            const userId = $(this).data('id'); // Get user ID from the button

            // Fetch all user details using an AJAX request
            $.ajax({
                url: 'get_user_details.php', // Endpoint to get user details
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    user_id: userId
                }),
                success: function(response) {
                    if (response.success) {
                        const user = response.data; // Fetch the user data from the response

                        // Display SweetAlert with input fields for all user details
                        Swal.fire({
                            title: 'Edit User Information',
                            html: `
                        <input type="text" id="userFullName" class="swal2-input" placeholder="Full Name" value="${user.user_full_name}">
                        <input type="email" id="userEmail" class="swal2-input" placeholder="Email" value="${user.user_email}">
                        <input type="text" id="userPhoneNumber" class="swal2-input" placeholder="Phone Number" value="${user.user_phone_number}">
                        <textarea id="userAddress" class="swal2-textarea" placeholder="Address">${user.user_address}</textarea>
                        <p>Registered Date: ${user.user_created_at}</p>
                    `,
                            showCancelButton: true,
                            confirmButtonText: 'Save',
                            preConfirm: () => {
                                // Fetch the updated values from the modal
                                const userFullName = Swal.getPopup()
                                    .querySelector('#userFullName').value;
                                const userEmail = Swal.getPopup().querySelector(
                                    '#userEmail').value;
                                const userPhoneNumber = Swal.getPopup()
                                    .querySelector('#userPhoneNumber').value;
                                const userAddress = Swal.getPopup()
                                    .querySelector('#userAddress').value;

                                if (!userFullName || !userEmail || !
                                    userPhoneNumber || !userAddress) {
                                    Swal.showValidationMessage(
                                        'Please fill out all fields.');
                                }

                                // Return the updated data
                                return {
                                    userFullName: userFullName,
                                    userEmail: userEmail,
                                    userPhoneNumber: userPhoneNumber,
                                    userAddress: userAddress
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Send AJAX request to update the user details in the database
                                $.ajax({
                                    url: 'edit_user.php',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        user_id: userId,
                                        user_full_name: result.value
                                            .userFullName,
                                        user_email: result.value
                                            .userEmail,
                                        user_phone_number: result
                                            .value.userPhoneNumber,
                                        user_address: result.value
                                            .userAddress
                                    }),
                                    success: function(response) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: response
                                                .success ?
                                                'success' :
                                                'error',
                                            title: response
                                                .message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        if (response.success) {
                                            setTimeout(() => location
                                                .reload(), 1600);
                                        }
                                    },
                                    error: function() {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                            width: '400px',
                                            showConfirmButton: true,
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                });
                            }
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


        // Delete User using SweetAlert2
        $('.delete-user').on('click', function() {
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_user.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            user_id: userId
                        }),
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => location.reload(), 1600);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Unable to Delete User',
                                    text: response.message,
                                    width: '400px',
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                width: '400px',
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
    </script>
</body>

</html>