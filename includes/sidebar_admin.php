<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Admin Sidebar -->

<div class="col-lg-2 sidebar p-0">

    <div class="sidebar-wrapper">

        <!-- Logo -->

        <div class="sidebar-logo text-center">

            <a href="../admin/dashboard.php" class="text-decoration-none">

                <i class="fas fa-user-shield logo-icon"></i>

                <h4 class="logo-text mt-2">SCPS</h4>

                <small class="text-light">
                    Admin Panel
                </small>

            </a>

        </div>

        <!-- Menu -->

        <ul class="sidebar-menu">

            <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'manage_students.php') ? 'active' : ''; ?>">
                <a href="manage_students.php">
                    <i class="fas fa-user-graduate"></i>
                    <span>Manage Students</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'manage_companies.php') ? 'active' : ''; ?>">
                <a href="manage_companies.php">
                    <i class="fas fa-building"></i>
                    <span>Manage Companies</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'manage_jobs.php') ? 'active' : ''; ?>">
                <a href="manage_jobs.php">
                    <i class="fas fa-briefcase"></i>
                    <span>Manage Jobs</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'manage_applications.php') ? 'active' : ''; ?>">
                <a href="manage_applications.php">
                    <i class="fas fa-file-alt"></i>
                    <span>Manage Applications</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
                <a href="reports.php">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li>
                <a href="../logout.php" id="sidebarLogout" class="logout-link">
                    <i class="fas fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const logoutBtn = document.getElementById("sidebarLogout");

        if (logoutBtn) {

            logoutBtn.addEventListener("click", function(e) {

                e.preventDefault();

                Swal.fire({
                    title: "Logout?",
                    text: "Are You Sure You Want To Logout?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#198754",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Logout",
                    cancelButtonText: "Cancel"
                }).then((result) => {

                    if (result.isConfirmed) {

                        window.location.href = "../admin/logout.php";

                    }

                });

            });

        }

    });
</script>