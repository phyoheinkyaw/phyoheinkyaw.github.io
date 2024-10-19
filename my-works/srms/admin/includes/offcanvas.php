<div class="offcanvas offcanvas-start bg-dark" tabindex="-1" id="offcanvasSidebar" data-bs-theme="dark">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Admin Dashboard</h5>
        <button type="button" class="btn btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled components">
            <!-- Dashboard Section -->
            <li>
                <a href="#dashboard" class="nav-link d-flex align-items-center text-white">
                    <i class="fas fa-tachometer-alt nav-icon"></i>Dashboard
                </a>
            </li>
            <hr class="sidebar-divider">

            <!-- User Management with Dropdown -->
            <li>
                <a href="#offcanvasUserManagementSubmenu" class="nav-link d-flex align-items-center text-white"
                    data-bs-toggle="collapse">
                    <i class="fas fa-users nav-icon"></i>User Management
                    <i class="fas fa-caret-down ms-auto dropdown-arrow"></i>
                </a>
                <ul class="collapse list-unstyled ps-4" id="offcanvasUserManagementSubmenu">
                    <li><a href="#allUsers" class="text-white"><i class="fas fa-user nav-icon"></i>All Users</a>
                    </li>
                    <li><a href="#addUser" class="text-white"><i class="fas fa-user-plus nav-icon"></i>Add User</a>
                    </li>
                </ul>
            </li>
            <hr class="sidebar-divider">

            <!-- Reports with Dropdown -->
            <li>
                <a href="#offcanvasReportsSubmenu" class="nav-link d-flex align-items-center text-white"
                    data-bs-toggle="collapse">
                    <i class="fas fa-chart-line nav-icon"></i>Reports
                    <i class="fas fa-caret-down ms-auto dropdown-arrow"></i>
                </a>
                <ul class="collapse list-unstyled ps-4" id="offcanvasReportsSubmenu">
                    <li><a href="#salesReport" class="text-white"><i
                                class="fas fa-file-invoice-dollar nav-icon"></i>Sales Report</a></li>
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
    </div>
</div>