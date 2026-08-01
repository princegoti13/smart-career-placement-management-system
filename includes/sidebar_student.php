<!-- Student Sidebar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <div class="sidebar-wrapper">

        <!-- Logo -->

        <div class="sidebar-logo text-center">

            <a href="../student/dashboard.php" class="text-decoration-none">

                <i class="fas fa-user-graduate logo-icon"></i>

                <h4 class="logo-text mt-2">
                    SCPS
                </h4>

                <small class="text-light">
                    Student Panel
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
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'edit_profile.php') ? 'active' : ''; ?>">
                <a href="edit_profile.php">
                    <i class="fas fa-user-pen"></i>
                    <span>Edit Profile</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'skills_resume.php') ? 'active' : ''; ?>">
                <a href="skills_resume.php">
                    <i class="fas fa-file-arrow-up"></i>
                    <span>Skills & Resume</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'view_jobs.php') ? 'active' : ''; ?>">
                <a href="view_jobs.php">
                    <i class="fas fa-briefcase"></i>
                    <span>View Jobs</span>
                </a>
            </li>

            <li class="<?php echo ($current_page == 'my_applications.php') ? 'active' : ''; ?>">
                <a href="my_applications.php">
                    <i class="fas fa-file-lines"></i>
                    <span>My Applications</span>
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

                        window.location.href = "../student/logout.php";

                    }

                });

            });

        }

    });
</script>

<!-- <script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script> -->

<!-- <script>
    $('.logout-btn').click(function(e) {

        e.preventDefault();

        // Remove focus immediately
        $(this).blur();

        Swal.fire({
            title: 'Logout?',
            text: 'Are You Sure You Want To Logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = 'logout.php';

            } else {

                // Remove focus after cancel
                $('.logout-btn').blur();

            }

        });

    });
</script> -->