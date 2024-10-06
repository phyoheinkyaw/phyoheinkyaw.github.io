<?php
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>All Notices - Student Result Management System</title>
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
                margin-bottom: 20px;
                transition: transform 0.3s ease;
            }
            .card-custom:hover {
                transform: scale(1.03);
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

        <!-- Header section with greeting message -->
        <header class="py-5 bg-header text-white">
            <div class="container text-center">
                <h1 class="display-4 fw-bold">All Notices</h1>
                <p class="lead">Stay informed with the latest updates and notices.</p>
            </div>
        </header>

        <!-- Notices Section -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <?php
                    $sql = "SELECT * from tblnotice ORDER BY postingDate DESC";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) { ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card card-custom h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlentities($result->noticeTitle); ?></h5>
                                        <p class="card-text text-muted">
                                            <?php echo substr(htmlentities($result->noticeDetails), 0, 100); ?>...
                                        </p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="notice-details.php?nid=<?php echo htmlentities($result->id); ?>" class="btn btn-primary">Read More</a>
                                    </div>
                                </div>
                            </div>
                    <?php } 
                    } else { ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">No notices available at the moment.</p>
                        </div>
                    <?php } ?>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    </body>
</html>
