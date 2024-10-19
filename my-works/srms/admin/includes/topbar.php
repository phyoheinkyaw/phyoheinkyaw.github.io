<nav class="navbar navbar-expand-lg navbar-light bg-light d-flex justify-content-end">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-primary me-auto">
            <i class="fas fa-align-left"></i>
        </button>

        <!-- Notification Icon -->
        <div class="dropdown me-3">
            <button class="btn btn-light" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                <li><a class="dropdown-item" href="#">New User Registered</a></li>
                <li><a class="dropdown-item" href="#">Server Warning</a></li>
                <li><a class="dropdown-item" href="#">New Order Received</a></li>
            </ul>
        </div>

        <!-- Profile Icon -->
        <div class="dropdown">
            <button class="btn btn-light" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="assets/img/male.png" alt="Admin Profile" class="rounded-circle" width="40">
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>