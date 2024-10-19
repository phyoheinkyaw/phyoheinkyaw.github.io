<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Sidebar for Desktop View -->
    <?php include_once 'includes/sidebar.php'; ?>

    <!-- Offcanvas Sidebar for Mobile View -->
    <?php include_once 'includes/offcanvas.php'; ?>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <?php include_once 'includes/topbar.php'; ?>

        <!-- Main Content -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-primary">
                        <a href="" class="text-decoration-none text-white">
                            <div class="card-body">
                                <?php 
                                    $sql1 = "SELECT StudentId from tblstudents";
                                    $query1 = $dbh->prepare($sql1);
                                    $query1->execute();
                                    $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                    $totalstudents = $query1->rowCount();
                                ?>
                                <h5 class="card-title counter"
                                    data-target="<?php echo htmlentities($totalstudents); ?>">0</h5>
                                <p class="card-text"><i class="fa fa-users nav-icon"></i>Total Students</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-warning">
                        <a href="" class="text-decoration-none text-white">
                            <div class="card-body">
                                <?php 
                                    $sql = "SELECT id from tblsubjects";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $totalsubjects = $query->rowCount();
                                ?>
                                <h5 class="card-title counter"
                                    data-target="<?php echo htmlentities($totalsubjects); ?>">0</h5>
                                <p class="card-text"><i class="fa fa-ticket nav-icon"></i>Subject Listed</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-danger">
                        <a href="" class="text-decoration-none text-white">
                            <div class="card-body">
                                <?php 
                                $sql2 ="SELECT id from  tblclasses ";
                                $query2 = $dbh -> prepare($sql2);
                                $query2->execute();
                                $results2=$query2->fetchAll(PDO::FETCH_OBJ);
                                $totalclasses=$query2->rowCount();
                            ?>
                                <h5 class="card-title counter" data-target="<?php echo htmlentities($totalclasses);?>">0
                                </h5>
                                <p class="card-text"><i class="fa fa-bank nav-icon"></i>Total Class Listed</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card bg-success">
                        <a href="" class="text-decoration-none text-white">
                            <div class="card-body">
                                <?php 
                                $sql3="SELECT  distinct StudentId from  tblresult ";
                                $query3 = $dbh -> prepare($sql3);
                                $query3->execute();
                                $results3=$query3->fetchAll(PDO::FETCH_OBJ);
                                $totalresults=$query3->rowCount();
                            ?>
                                <h5 class="card-title counter" data-target="<?php echo htmlentities($totalresults);?>">0
                                </h5>
                                <p class="card-text"><i class="fa fa-file-text nav-icon"></i>Result Declared</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>

</html>