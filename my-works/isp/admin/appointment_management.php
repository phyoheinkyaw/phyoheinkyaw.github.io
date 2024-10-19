<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Fetch all appointments from the database
$query = "SELECT appointment_id, user.user_full_name, isp_name, appointment_date, appointment_status 
          FROM appointment
          INNER JOIN user ON appointment.user_id = user.user_id
          INNER JOIN isp ON appointment.isp_id = isp.isp_id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management - ISP Connect</title>
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
                    <h4>Appointment Management</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="appointmentTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>ISP</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['appointment_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['isp_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                        <td>
                                            <?php
        $statusText = '';
        switch ($row['appointment_status']) {
            case '1':
                $statusText = '<span class="badge bg-success">Confirmed</span>';
                break;
            case '0':
                $statusText = '<span class="badge bg-danger">Cancelled</span>';
                break;
            case '9':
                $statusText = '<span class="badge bg-warning text-dark">Pending</span>';
                break;
        }
        echo $statusText;
    ?>
                                        </td>

                                        <td>
                                            <button class="btn btn-info btn-sm view-appointment"
                                                data-id="<?php echo $row['appointment_id']; ?>">View</button>
                                            <!-- <button class="btn btn-primary btn-sm edit-appointment"
                                                data-id="<?php echo $row['appointment_id']; ?>">Edit</button>
                                            <button class="btn btn-danger btn-sm cancel-appointment"
                                                data-id="<?php echo $row['appointment_id']; ?>">Cancel</button> -->
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6">No appointments found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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
        $('#appointmentTable').DataTable({

            autoWidth: false, // To prevent columns from breaking layout in mobile
            columnDefs: [{
                    targets: [0, 3, 4],
                    className: 'dt-body-center'
                } // Center align for better look
            ],
            language: {
                paginate: {
                    previous: "<",
                    next: ">"
                }
            }
        });

        // View and Edit Appointment using SweetAlert2
        $('.view-appointment').on('click', function() {
            const appointmentId = $(this).data('id');

            // Fetch appointment details via AJAX
            $.ajax({
                url: 'get_appointment_details.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    appointment_id: appointmentId
                }),
                success: function(response) {
                    if (response.success) {
                        const appointment = response.data;

                        // Define status options
                        const statusOptions = `
                        <select id="appointmentStatus" class="form-select">
                            <option value="9" ${appointment.appointment_status == '9' ? 'selected' : ''}>Pending</option>
                            <option value="1" ${appointment.appointment_status == '1' ? 'selected' : ''}>Confirmed</option>
                            <option value="0" ${appointment.appointment_status == '0' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    `;

                        // Show SweetAlert with all appointment details
                        Swal.fire({
                            title: 'Appointment Details',
                            html: `
    <div class="table-responsive">
        <table class="table table-borderless text-start">
            <tbody>
                <tr>
                    <td><strong>User:</strong></td>
                    <td>${appointment.user_full_name}</td>
                </tr>
                <tr>
                    <td><strong>ISP:</strong></td>
                    <td>${appointment.isp_name}</td>
                </tr>
                <tr>
                    <td><strong>Phone Number:</strong></td>
                    <td>${appointment.appointment_phone_number}</td>
                </tr>
                <tr>
                    <td><strong>Address:</strong></td>
                    <td>${appointment.appointment_address}</td>
                </tr>
                <tr>
                    <td><strong>Appointment Date:</strong></td>
                    <td>${appointment.appointment_date}</td>
                </tr>
                <tr>
                    <td><strong>Payment Screenshot:</strong></td>
                    <td>
                        <img src="img/payment/${appointment.appointment_payment_screenshot}" alt="Payment Screenshot" class="img-fluid" style="max-width: 100px; height: auto;" />
                        <a href="#" onclick="viewFullSizeImage('img/payment/${appointment.appointment_payment_screenshot}')" class="d-block mt-2">( View Full Size )</a>
                    </td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>${statusOptions}</td>
                </tr>
            </tbody>
        </table>
    </div>
`,
                            showCancelButton: true,
                            confirmButtonText: 'Save Changes',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                const appointmentStatus = Swal.getPopup()
                                    .querySelector('#appointmentStatus').value;
                                return {
                                    appointmentStatus: appointmentStatus
                                };
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Update appointment status via AJAX
                                $.ajax({
                                    url: 'update_appointment_status.php',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        appointment_id: appointmentId,
                                        appointment_status: result
                                            .value.appointmentStatus
                                    }),
                                    success: function(updateResponse) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: updateResponse
                                                .success ?
                                                'success' :
                                                'error',
                                            title: updateResponse
                                                .message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        if (updateResponse.success) {
                                            setTimeout(() => location
                                                .reload(), 1600);
                                        }
                                    },
                                    error: function() {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                            showConfirmButton: true
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
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: true
                    });
                }
            });
        });
    });

    function viewFullSizeImage(imageUrl) {
        Swal.fire({
            title: 'Payment Screenshot',
            imageUrl: imageUrl,
            imageAlt: 'Full Size Payment Screenshot',
            showCloseButton: true,
            showConfirmButton: false,
            padding: '1rem',
            imageHeight: 'auto',
            imageWidth: '100%',
        });
    }
    </script>
</body>

</html>