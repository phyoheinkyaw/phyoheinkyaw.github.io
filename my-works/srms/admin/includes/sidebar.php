<nav id="sidebar" class="bg-dark text-white vh-100 position-fixed">
    <div class="sidebar-header p-3">
        <h4>Admin Dashboard</h4>
        <hr class="sidebar-divider">
    </div>
    <ul class="list-unstyled components px-3">
        <!-- Dashboard Section -->
        <li>
            <a href="#dashboard" class="nav-link d-flex align-items-center text-white">
                <i class="fas fa-tachometer-alt nav-icon"></i>Dashboard
            </a>
        </li>
        <hr class="sidebar-divider">

        <!-- User Management with Dropdown -->
        <li>
            <a href="#userManagementSubmenu" class="nav-link d-flex align-items-center text-white"
                data-bs-toggle="collapse">
                <i class="fas fa-users nav-icon"></i>User Management
                <i class="fas fa-caret-down ms-auto dropdown-arrow"></i>
            </a>
            <ul class="collapse list-unstyled ps-4" id="userManagementSubmenu">
                <li><a href="#allUsers" class="text-white"><i class="fas fa-user nav-icon"></i>All Users</a></li>
                <li><a href="#addUser" class="text-white"><i class="fas fa-user-plus nav-icon"></i>Add User</a>
                </li>
            </ul>
        </li>
        <hr class="sidebar-divider">

        <!-- Reports with Dropdown -->
        <li>
            <a href="#reportsSubmenu" class="nav-link d-flex align-items-center text-white" data-bs-toggle="collapse">
                <i class="fas fa-chart-line nav-icon"></i>Reports
                <i class="fas fa-caret-down ms-auto dropdown-arrow"></i>
            </a>
            <ul class="collapse list-unstyled ps-4" id="reportsSubmenu">
                <li><a href="#salesReport" class="text-white"><i class="fas fa-file-invoice-dollar nav-icon"></i>Sales
                        Report</a></li>
                <li><a href="#userReport" class="text-white"><i class="fas fa-user nav-icon"></i>User Report</a>
                </li>
            </ul>
        </li>
        <hr class="sidebar-divider">

        <!-- Settings Section -->
        <li>
            <a href="#settings" class="nav-link d-flex align-items-center text-white">
                <i class="fas fa-cogs nav-icon"></i>Settings
            </a>
        </li>
        <hr class="sidebar-divider">
    </ul>
</nav>