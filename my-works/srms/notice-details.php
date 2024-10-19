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
            <h2 class="display-6 mb-4">Notice Details</h2>
            <p class="lead">View the details of the selected notice.</p>
        </div>
    </header>
    <!-- Greeting Header End -->

    <!-- Main Section Start -->
    <section class="pt-5 pb-4">
        <div class="container">
            <nav class="align-self-center" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page"><a href="allnotice.php">All Notices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo htmlentities($result->noticeTitle); ?></li>
                </ol>
            </nav>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow p-4">
                        <div class="card-body">
                            <h3 class="card-title fw-bold"><?php echo htmlentities($result->noticeTitle); ?></h3>
                            <p class="text-muted">Posted on: <?php echo htmlentities($result->postingDate); ?></p>
                            <hr />
                            <p class="card-text" style="text-align: justify;">
                                <?php echo html_entity_decode($result->noticeDetails); ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="allnotice.php" class="btn btn-secondary">&larr; Back to Notices</a>
                    </div>
                </div>
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