<?php
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Result Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

    <!-- Navigation Start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-navigation">
        <div class="container">
            <a href="index.html" class="navbar-brand fw-bold">SRMS</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="allnotice.php" class="nav-link">All Notice</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navigation End -->

    <!-- Greeting Header Start -->
    <header class="py-5 bg-header text-light">
        <div class="container text-center">
            <h2 class="display-6 mb-4">Welcome to Student Result Management System</h2>
            <p class="lead">Your one-stop portal for viewing student results and important notices.</p>
        </div>
    </header>
    <!-- Greeting Header End -->

    <!-- Main Section Start -->
    <section class="pt-5 pb-4">
        <div class="container">
            <div class="row d-flex">
                <!-- Notice Start -->
                <div class="col-lg-4 mb-4">
                    <div class="card p-1 w-100 shadow card-notice">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title text-center mb-4">Notice Board</h3>
                            <div class="notice-board flex-grow-1">
                                <marquee scrollamount="3" direction="up" onmouseover="this.stop();"
                                    onmouseout="this.start();">
                                    <ul class="marquee-list">
                                        <?php
                                            $sql = "SELECT * from tblnotice";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) { ?>
                                        <li><a href="notice-details.php?nid=<?php echo htmlentities($result->id); ?>"
                                                target="_blank"><i class="bi bi-megaphone-fill"></i>
                                                <?php echo htmlentities($result->noticeTitle); ?></a></li>
                                        <?php }} ?>
                                    </ul>
                                </marquee>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notice End -->
                <!-- Result Form Start -->
                <div class="col-lg-8">
                    <div class="card p-4 w-100 shadow">
                        <h3 class="mb-4">Find Your Result</h3>
                        <form action="" method="">
                            <div class="mb-3">
                                <label for="roll-id" class="form-label">Enrollment ID</label>
                                <input type="text" name="roll-id" id="roll-id" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label for="class" class="form-label">Class</label>
                                <select name="class" id="class" class="form-select" required>
                                    <option disabled selected>Select Your Class</option>
                                    <option>Class 1</option>
                                    <option>Class 2</option>
                                    <option>Class 3</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Find Result</button>
                        </form>
                    </div>
                </div>
                <!-- Result Form End -->
            </div>
        </div>
    </section>
    <!-- Main Section End -->

    <!-- Footer Start -->
    <footer class="py-4 bg-dark text-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="m-0">&copy; 2024 Student Result Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#" class="text-light" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-light" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-light" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-light" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" type="text/javascript">
    </script>

</body>

</html>