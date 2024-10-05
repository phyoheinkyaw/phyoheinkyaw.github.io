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
                    <li class="sidebar-item">
                        <a href="user_management.php" class="sidebar-link"><i class="fa-solid fa-users"></i> Users</a>
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
                                    <a href="#" class="footer-instagram"><i
                                            class="fa-brands fa-square-instagram"></i></a>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#userTable').DataTable();

        // Edit User using SweetAlert2
        $('.edit-user').on('click', function() {
            const userId = $(this).data('id');
            const row = $(this).closest('tr');
            const currentFullName = row.find('td:eq(1)').text();
            const currentEmail = row.find('td:eq(2)').text();
            const currentPhoneNumber = row.find('td:eq(3)').text();

            Swal.fire({
                title: 'Edit User Information',
                html: `
                <input type="text" id="userFullName" class="swal2-input" placeholder="Full Name" value="${currentFullName}">
                <input type="email" id="userEmail" class="swal2-input" placeholder="Email" value="${currentEmail}">
                <input type="text" id="userPhoneNumber" class="swal2-input" placeholder="Phone Number" value="${currentPhoneNumber}">
                <p>Registered Date: ${row.find('td:eq(4)').text()}</p>
            `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    const userFullName = Swal.getPopup().querySelector('#userFullName')
                        .value;
                    const userEmail = Swal.getPopup().querySelector('#userEmail').value;
                    const userPhoneNumber = Swal.getPopup().querySelector(
                        '#userPhoneNumber').value;

                    if (!userFullName || !userEmail || !userPhoneNumber) {
                        Swal.showValidationMessage(`Please fill out all fields.`);
                    }
                    return {
                        userFullName: userFullName,
                        userEmail: userEmail,
                        userPhoneNumber: userPhoneNumber
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to update user
                    $.ajax({
                        url: 'edit_user.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            user_id: userId,
                            user_full_name: result.value.userFullName,
                            user_email: result.value.userEmail,
                            user_phone_number: result.value.userPhoneNumber
                        }),
                        success: function(response) {
                            Swal.fire({
                                position: 'top-end',
                                icon: response.success ? 'success' :
                                    'error',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (response.success) {
                                setTimeout(() => location.reload(), 1600);
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