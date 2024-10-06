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
        <title>Student Result Management System</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap CSS (via CDN)-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bg-header {
                background: linear-gradient(to right, #0062E6, #33AEFF);
            }
            .notice-board {
                max-height: 100%;
                overflow: hidden;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
            }
            .card-custom {
                border: none;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                border-radius: 15px;
                height: 100%;
            }
            .marquee-list {
                list-style-type: none;
                padding-left: 0;
                margin-bottom: 0;
            }
            .marquee-list li {
                margin-bottom: 10px;
            }
            .marquee-list li a {
                text-decoration: none;
                color: #007BFF;
                font-weight: 500;
            }
            .marquee-list li a:hover {
                color: #0056b3;
            }
            .equal-height {
                display: flex;
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
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-center">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="allnotice.php">All Notice</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Header section with greeting message -->
        <header class="py-5 bg-header text-white">
            <div class="container text-center">
                <h1 class="display-4 fw-bold">Welcome to the Student Result Management System</h1>
                <p class="lead">Your one-stop portal for viewing student results and important notices.</p>
            </div>
        </header>

        <!-- Main Content Section with Notice Board on Left and Form on Right -->
        <section class="py-5">
            <div class="container">
                <div class="row equal-height">
                    <!-- Notice Board (Left Sidebar) -->
                    <div class="col-lg-4 mb-4 d-flex">
                        <div class="card card-custom w-100">
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title text-center mb-4">Notice Board</h3>
                                <div class="notice-board flex-grow-1">
                                    <marquee direction="up" onmouseover="this.stop();" onmouseout="this.start();" class="flex-grow-1">
                                        <ul class="marquee-list">
                                            <?php
                                            $sql = "SELECT * from tblnotice";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                                    <li><a href="notice-details.php?nid=<?php echo htmlentities($result->id); ?>" target="_blank"><i class="bi bi-megaphone-fill"></i> <?php echo htmlentities($result->noticeTitle); ?></a></li>
                                            <?php }} ?>
                                        </ul>
                                    </marquee>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Find Result Form (Right Content) -->
                    <div class="col-lg-8 d-flex">
                        <div class="card card-custom p-4 w-100">
                            <h3 class="text-center mb-4">Find Your Result</h3>
                            <form action="find-result.php" method="post">
                                <div class="mb-3">
                                    <label for="rollId" class="form-label">Roll ID</label>
                                    <input type="text" class="form-control" id="rollId" name="rollId" required>
                                </div>
                                <div class="mb-3">
                                    <label for="class" class="form-label">Class</label>
                                    <select class="form-select" id="class" name="class" required>
                                        <option value="">Select Class</option>
                                        <option value="1">Class 1</option>
                                        <option value="2">Class 2</option>
                                        <option value="3">Class 3</option>
                                        <!-- Add more classes as needed -->
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Find Result</button>
                                </div>
                            </form>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    </body>
</html>
