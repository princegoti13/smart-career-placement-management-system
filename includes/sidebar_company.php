<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Company Sidebar -->

<div class="col-lg-2 sidebar p-0">

    <div class="sidebar-wrapper">

        <!-- Logo -->

        <div class="sidebar-logo text-center">

            <a href="../company/dashboard.php" class="text-decoration-none">

                <i class="fas fa-building logo-icon"></i>

                <h4 class="logo-text mt-2">SCPS</h4>

                <small class="text-light">
                    Company Panel
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

            <li class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
                <a href="profile.php">
                    <i class="fas fa-building"></i>
                    <span>Profile</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'edit_profile.php') ? 'active' : ''; ?>">
                <a href="edit_profile.php">
                    <i class="fas fa-user-pen"></i>
                    <span>Edit Profile</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'add_job.php') ? 'active' : ''; ?>">
                <a href="add_job.php">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Job</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'manage_jobs.php') ? 'active' : ''; ?>">
                <a href="manage_jobs.php">
                    <i class="fas fa-briefcase"></i>
                    <span>Manage Jobs</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'view_applications.php') ? 'active' : ''; ?>">
                <a href="view_applications.php">
                    <i class="fas fa-users"></i>
                    <span>View Applicants</span>
                </a>
            </li>

            <li>
                <a href="logout.php" id="sidebarLogout" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
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

                const logoutUrl = this.getAttribute("href");

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

                        window.location.href = logoutUrl;

                    }

                });

            });

        }

    });
</script>