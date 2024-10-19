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
            <h2 class="display-6 mb-4">All Notices</h2>
            <p class="lead">Stay informed with the latest updates and notices.</p>
        </div>
    </header>
    <!-- Greeting Header End -->

    <!-- Main Section Start -->
    <section class="pt-5 pb-4">
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
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title p-2 pb-0"><?php echo htmlentities($result->noticeTitle); ?></h5>
                            <p class="card-text text-muted p-2 pb-0">
                                <?php echo substr(strip_tags(html_entity_decode($result->noticeDetails)), 0, 100); ?>...
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="notice-details.php?nid=<?php echo htmlentities($result->id); ?>"
                                class="btn btn-primary w-50">Read More</a>
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
    <!-- Main Section End -->

    <!-- Footer Start -->
    <footer class="py-3 bg-navigation text-light">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-6">
                    <p class="m-0">&copy; 2024 Student Result Management System. All right reserved.</p>
                </div>
                <div class="col-md-6">
                    <ul class="list-inline mt-3">
                        <li class="list-inline-item"><a href="">Facebook</a></li>
                        <li class="list-inline-item"><a href="">Facebook</a></li>
                        <li class="list-inline-item"><a href="">Facebook</a></li>
                        <li class="list-inline-item"><a href="">Facebook</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer End -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" type="text/javascript">
    </script>
</body>

</html>