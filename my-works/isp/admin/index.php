<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ISP Connect | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
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
                    <h4>Admin Dashboard</h4>
                </div>
                <div class="row">
                    <div class="col-6 bg-danger">
                        dfafda
                    </div>
                    <div class="col-6 bg-success">
                        dfafda
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" type="text/javascript">
    </script>
    <script src="js/script.js" type="text/javascript"></script>
</body>

</html>