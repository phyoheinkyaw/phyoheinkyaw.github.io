<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login if not logged in
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Check if the current admin is a super admin
$is_super_admin = ($_SESSION['admin_id'] == 1);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - ISP Connect</title>
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
                    <h4>Admin Management</h4>
                </div>

                <?php if (!$is_super_admin): ?>
                <!-- Non-super admin access -->
                <div class="alert alert-danger">
                    <strong>Error:</strong> You can't access this page.
                </div>
                <?php else: ?>
                <!-- Super Admin Access: Show Add Admin Form and Admin Table -->
                <div class="card mb-4">
                    <div class="card-header">Add New Admin</div>
                    <div class="card-body">
                        <form id="addAdminForm">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="adminFullName" class="form-label">Full Name</label>
                                    <input type="text" id="adminFullName" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="adminEmail" class="form-label">Email</label>
                                    <input type="email" id="adminEmail" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="adminPhoneNumber" class="form-label">Phone Number</label>
                                    <input type="text" id="adminPhoneNumber" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="adminPassword" class="form-label">Password</label>
                                    <input type="password" id="adminPassword" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Add Admin</button>
                        </form>
                    </div>
                </div>

                <!-- Admin Table -->
                <div class="row">
                    <div class="col-md-12">
                        <table id="adminTable" class="table table-striped">
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
                                <?php
                                    // Fetch all admins from the database
                                    $query = "SELECT admin_id, admin_full_name, admin_email, admin_phone_number, DATE(admin_created_at) as admin_created_at FROM admin";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['admin_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['admin_full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['admin_email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['admin_phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['admin_created_at']); ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-admin"
                                            data-id="<?php echo $row['admin_id']; ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6">No admins found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
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
        // Initialize DataTable
        $('#adminTable').DataTable();

        // Handle Add Admin Form Submission
        $('#addAdminForm').on('submit', function(e) {
            e.preventDefault();

            const adminFullName = $('#adminFullName').val();
            const adminEmail = $('#adminEmail').val();
            const adminPhoneNumber = $('#adminPhoneNumber').val();
            const adminPassword = $('#adminPassword').val();

            // Send AJAX request to add new admin
            $.ajax({
                url: 'add_admin.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    admin_full_name: adminFullName,
                    admin_email: adminEmail,
                    admin_phone_number: adminPhoneNumber,
                    admin_password: adminPassword
                }),
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
        });

        // Delete Admin
        $('.delete-admin').on('click', function() {
            const adminId = $(this).data('id');

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
                        url: 'delete_admin.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            admin_id: adminId
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
                                    title: 'Unable to Delete Admin',
                                    text: response.message,
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