<?php
// $photo = !empty($student['profile_photo'])
//     ? "../assets/uploads/profiles/" . $student['profile_photo']
//     : "../assets/images/default-user.png";

if (
    !empty($student['profile_photo']) &&
    $student['profile_photo'] != "default-user.png"
) {

    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
} else {

    $photo = "../assets/images/default-user.png";
}
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3 border-bottom">

    <div class="container-fluid">

        <!-- Mobile Sidebar Button -->

        <button class="btn btn-outline-primary d-lg-none me-3" id="sidebarToggle">

            <i class="fas fa-bars"></i>

        </button>

        <!-- Page Title -->

        <h3 class="fw-bold mb-0">
            <?php echo isset($page_title) ? $page_title : "Dashboard"; ?>
        </h3>

        <div class="ms-auto d-flex align-items-center">

            <!-- Notification -->

            <button class="btn btn-light position-relative me-3">

                <i class="fas fa-bell"></i>

                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                    3

                </span>

            </button>

            <!-- User Dropdown -->

            <div class="dropdown">

                <a href="#"

                    class="d-flex align-items-center text-decoration-none"

                    data-bs-toggle="dropdown">

                    <img
                        src="<?php echo $photo; ?>"
                        class="rounded-circle"
                        width="40"
                        height="40"
                        alt="Profile">

                    <div class="ms-2 d-none d-md-block">

                        <h6 class="mb-0 fw-semibold">

                            Welcome

                        </h6>

                        <small class="text-muted">

                            <?php echo isset($student['full_name']) ? htmlspecialchars($student['full_name']) : 'User'; ?>

                        </small>

                    </div>

                    <i class="fas fa-chevron-down ms-2 text-secondary"></i>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>

                        <a class="dropdown-item" href="../student/profile.php">

                            <i class="fas fa-user me-2"></i>

                            My Profile

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item" href="../student/edit_profile.php">

                            <i class="fas fa-user-edit me-2"></i>

                            Edit Profile

                        </a>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item text-danger logout-btn" id="topbarLogout" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const logoutBtn = document.getElementById("topbarLogout");

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

                        window.location.href = "../company/logout.php";

                    }

                });

            });

        }

    });
</script>