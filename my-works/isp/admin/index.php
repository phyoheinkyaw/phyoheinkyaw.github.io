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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
	<link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>

	<div class="wrapper">
		<aside id="sidebar" class="js-sidebar">
			<!-- Sidebar Content Start -->
			<div class="h-100">
				<div class="sidebar-logo">
					<a href="">ISP Connect</a>
				</div>
				<ul class="sidebar-nav">
					<li class="sidebar-header">Admin Elements</li>
					<li class="sidebar-item">
						<a href="#" class="sidebar-link"><i class="fa-solid fa-list"></i> Dashboard</a>
					</li>
					<li class="sidebar-item">
						<a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"><i class="fa-solid fa-file-lines"></i> Pages</a>
						<ul id="pages" class="sidebar-dropdown collapse" data-bs-parent="#sidebar">
							<li class="sidebar-item">
								<a href="#" class="sidebar-link">Page 1</a>
							</li>
							<li class="sidebar-item">
								<a href="#" class="sidebar-link">Page 2</a>
							</li>
							<li class="sidebar-item">
								<a href="#" class="sidebar-link">Page 3</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-item">
						<a href="#" class="sidebar-link"><i class="fa-solid fa-list"></i> Dashboard</a>
					</li>
				</ul>
			</div>
			<!-- Sidebar Content End -->
		</aside>
		<!-- Main DIV Start -->
		<div class="main">
			
			<nav class="navbar navbar-expand border-bottom px-3">
				<button type="button" class="btn" id="sidebar-toggle">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="navbar-collapse navbar">
					<ul class="navbar-nav">
						<li class="nav-item dropdown">
							<a href="" data-bs-toggle="dropdown" class="nav-icon">
								<img src="img/male.png" class="avatar img-fluid" alt="Admin Profile Picture" />
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a href="admin_profile.php" class="dropdown-item">Profile</a>
								<a href="#" class="dropdown-item">Setting</a>
								<a href="logout.php" class="dropdown-item">Logout</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

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
		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-6 text-start">
						<p>ISP Connect</p>
					</div>
					<div class="col-6 text-end">
						<ul class="list-inline">
							<li class="list-inline-item">
								<a href="#" class="footer-facebook"><i class="fa-brands fa-square-facebook"></i></a>
							</li>
							<li class="list-inline-item">
								<a href="#" class="footer-instagram"><i class="fa-brands fa-square-instagram"></i></a>
							</li>
							<li class="list-inline-item">
								<a href="#" class="footer-telegram"><i class="fa-brands fa-telegram"></i></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
	<!-- Footer End -->

		</div>
		<!-- Main DIV End -->
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
	<script src="js/script.js" type="text/javascript"></script>
</body>
</html>