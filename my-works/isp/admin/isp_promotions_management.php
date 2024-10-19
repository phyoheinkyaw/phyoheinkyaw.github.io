<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login if not logged in
    header("Location: admin_login.php");
    exit;
}

require_once '../include/db_connection.php';

// Query to get ISPs
$sql = "SELECT isp_id, isp_name FROM isp";
$result = $conn->query($sql);

// Store ISPs in an array for later use
$ispOptions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ispOptions[] = $row;
    }
} else {
    echo "No ISPs found.";
}

// Query to get promotions and associated ISP details
$sql = "SELECT p.promotion_id, p.promotion_text, p.promotion_created_at, i.isp_name 
        FROM isp_promotion p 
        JOIN isp i ON p.isp_id = i.isp_id";
$result = $conn->query($sql);

// Store promotions in an array for later use
$promotions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }
} else {
    echo "No promotions found.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Promotions Management</title>
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
                    <h4 class="my-4">ISP Promotions Management</h4>
                </div>

                <!-- Form to Add/Edit Promotion -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 id="formTitle">Add Promotion</h4>
                        <form id="promotionForm">
                            <input type="hidden" id="promoId" value="">
                            <div class="mb-3">
                                <label for="ispSelect" class="form-label">Select ISP</label>
                                <select id="ispSelect" name="isp_id" class="form-control" required>
                                    <?php foreach($ispOptions as $isp): ?>
                                    <option value="<?php echo $isp['isp_id']; ?>">
                                        <?php echo $isp['isp_name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="promotionText" class="form-label">Promotion Text</label>
                                <textarea id="promotionText" name="promotion_text" class="form-control"
                                    required></textarea>
                            </div>
                            <button type="submit" id="submitButton" class="btn btn-success">Add Promotion</button>
                            <button type="button" id="cancelButton" class="btn btn-secondary"
                                style="display:none;">Cancel</button>
                        </form>
                    </div>
                </div>

                <!-- Table to Display Promotions -->
                <table id="promotionTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ISP Name</th>
                            <th>Promotion Text</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($promotions as $promo): ?>
                        <tr>
                            <td><?php echo $promo['promotion_id']; ?></td>
                            <td><?php echo $promo['isp_name']; ?></td>
                            <td><?php echo $promo['promotion_text']; ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-isp"
                                    data-id="<?php echo $row['isp_id']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm delete-isp"
                                    data-id="<?php echo $row['isp_id']; ?>">Delete</button>
                            </td>
                        </tr>
                        </option>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- Main Content End -->

        <!-- Footer Start -->
        <?php include_once 'admin_include/footer.php'; ?>
        <!-- Footer End -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/script.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {
        let isEditing = false; // Track if we are in edit mode

        // Initialize DataTable
        $('#promotionTable').DataTable();

        // Handle Add/Edit Promotion Form Submission
        $('#promotionForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const promoId = $('#promoId')
                .val(); // Get Promotion ID from hidden field to determine whether to edit

            if (isEditing) {
                formData.append('promotion_id', promoId);
                // Update Promotion (Edit Mode)
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
                // Add new Promotion (Add Mode)
                $.ajax({
                    url: 'add_promotion.php',
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

    });
    </script>
</body>

</html>