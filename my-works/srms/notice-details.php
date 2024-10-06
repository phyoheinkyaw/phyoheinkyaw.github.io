<?php
error_reporting(0);
include('includes/config.php');

// Get notice ID from query parameter
$nid = intval($_GET['nid']);

if ($nid == 0) {
    echo "<script>alert('Invalid Notice ID.');</script>";
    echo "<script>window.location.href = 'allnotice.php';</script>";
    exit;
}

// Fetch notice details
$sql = "SELECT * FROM tblnotice WHERE id = :nid";
$query = $dbh->prepare($sql);
$query->bindParam(':nid', $nid, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
if (!$result) {
    echo "<script>alert('Notice not found.');</script>";
    echo "<script>window.location.href = 'allnotice.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Notice Details - Student Result Management System</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap CSS (via CDN)-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bg-header {
                background: linear-gradient(to right, #0062E6, #33AEFF);
            }
            .card-custom {
                border: none;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                border-radius: 15px;
                transition: transform 0.3s ease;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">SRMS - Student Result Management System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="allnotice.php">All Notice</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Header section -->
        <header class="py-5 bg-header text-white">
            <div class="container text-center">
                <h1 class="display-4 fw-bold">Notice Details</h1>
                <p class="lead">View the details of the selected notice below.</p>
            </div>
        </header>

        <!-- Notice Details Section -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card card-custom p-4">
                            <div class="card-body">
                                <h3 class="card-title fw-bold"><?php echo htmlentities($result->noticeTitle); ?></h3>
                                <p class="text-muted">Posted on: <?php echo htmlentities($result->postingDate); ?></p>
                                <hr>
                                <p class="card-text">
                                    <?php echo nl2br(htmlentities($result->noticeDetails)); ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <a href="allnotice.php" class="btn btn-secondary">Back to Notices</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer section -->
        <footer class="py-5 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">&copy; Student Result Management System 2023</p>
            </div>
        </footer>

        <!-- Bootstrap Bundle JS (via CDN) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
