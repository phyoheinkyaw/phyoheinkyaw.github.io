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
    while ($row = $result->fetch_assoc()) {
        $ispOptions[] = $row;
    }
} else {
    echo "No ISPs found.";
}

// Query to get subscription plans and associated ISP details
$sql = "SELECT sp.plan_id, sp.plan_name, sp.plan_speed, sp.plan_price_per_month, sp.plan_features, i.isp_name
        FROM subscription_plan sp
        JOIN isp i ON sp.isp_id = i.isp_id";
$result = $conn->query($sql);

// Store plans in an array for later use
$plans = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plans[] = $row;
    }
} else {
    echo "No subscription plans found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Subscription Plans Management</title>
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
                    <h4 class="my-4">ISP Subscription Plans Management</h4>
                </div>

                <!-- Form to Add/Edit Subscription Plan -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 id="formTitle">Add Subscription Plan</h4>
                        <form id="planForm">
                            <input type="hidden" id="planId" value="">
                            <div class="mb-3">
                                <label for="ispSelect" class="form-label">Select ISP</label>
                                <select id="ispSelect" name="isp_id" class="form-control" required>
                                    <?php foreach ($ispOptions as $isp): ?>
                                    <option value="<?php echo $isp['isp_id']; ?>">
                                        <?php echo $isp['isp_name']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="planName" class="form-label">Plan Name</label>
                                <input type="text" id="planName" name="plan_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="planSpeed" class="form-label">Plan Speed</label>
                                <input type="text" id="planSpeed" name="plan_speed" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="planPrice" class="form-label">Plan Price (per month)</label>
                                <input type="number" id="planPrice" name="plan_price_per_month" class="form-control"
                                    min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="planFeatures" class="form-label">Plan Features</label>
                                <textarea id="planFeatures" name="plan_features" class="form-control"
                                    required></textarea>
                            </div>
                            <button type="submit" id="submitButton" class="btn btn-success">Add Plan</button>
                            <button type="button" id="cancelButton" class="btn btn-secondary"
                                style="display:none;">Cancel</button>
                        </form>
                    </div>
                </div>

                <!-- Table to Display Subscription Plans -->
                <table id="planTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ISP Name</th>
                            <th>Plan Name</th>
                            <th>Plan Speed</th>
                            <th>Plan Price</th>
                            <th>Features</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $plan): ?>
                        <tr>
                            <td><?php echo $plan['plan_id']; ?></td>
                            <td><?php echo $plan['isp_name']; ?></td>
                            <td><?php echo $plan['plan_name']; ?></td>
                            <td><?php echo $plan['plan_speed']; ?></td>
                            <td><?php echo $plan['plan_price_per_month']; ?></td>
                            <td><?php echo $plan['plan_features']; ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-plan"
                                    data-id="<?php echo $plan['plan_id']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm delete-plan"
                                    data-id="<?php echo $plan['plan_id']; ?>">Delete</button>
                            </td>
                        </tr>
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
        let isEditing = false;

        // Initialize DataTable
        $('#planTable').DataTable();

        // Edit button click event
        $(document).on('click', '.edit-plan', function() {
            const planId = $(this).data('id');

            // Fetch plan details
            $.ajax({
                url: 'fetch_plan.php',
                method: 'GET',
                data: {
                    plan_id: planId
                },
                success: function(response) {
                    if (response.success) {
                        $('#planId').val(response.data.plan_id);
                        $('#ispSelect').val(response.data.isp_id);
                        $('#planName').val(response.data.plan_name);
                        $('#planSpeed').val(response.data.plan_speed);
                        $('#planPrice').val(response.data.plan_price_per_month);
                        $('#planFeatures').val(response.data.plan_features);

                        $('#formTitle').text('Edit Subscription Plan');
                        $('#submitButton').text('Update Plan');
                        $('#cancelButton').show();

                        isEditing = true;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        });

        // Cancel button click event
        $('#cancelButton').on('click', function() {
            $('#planForm')[0].reset();
            $('#planId').val('');
            $('#formTitle').text('Add Subscription Plan');
            $('#submitButton').text('Add Plan');
            $('#cancelButton').hide();
            isEditing = false;
        });

        $(document).on('click', '.delete-plan', function() {
            const planId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send request to delete the plan
                    $.ajax({
                        url: 'delete_plan.php',
                        method: 'POST',
                        data: {
                            plan_id: planId
                        },
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
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            });
                        }
                    });
                }
            });
        });

        // Form submission for Add/Edit
        $('#planForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const planId = $('#planId').val();

            const url = isEditing ? 'edit_plan.php' : 'add_plan.php';

            if (isEditing) {
                formData.append('plan_id', planId);
            }

            $.ajax({
                url: url,
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
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>