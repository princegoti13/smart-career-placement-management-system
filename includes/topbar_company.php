<?php

/** @var mysqli $conn */

if (!isset($company)) {

    $company_id = $_SESSION['company_id'];

    $result = mysqli_query($conn, "SELECT * FROM companies WHERE id='$company_id' LIMIT 1");

    $company = mysqli_fetch_assoc($result);
}

if (
    !empty($company['company_logo']) &&
    $company['company_logo'] != "default-company.png"
) {

    $photo = "../uploads/company/" . $company['company_logo'];
} else {

    $photo = "../assets/images/company.png";
}
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3 border-bottom">

    <div class="container-fluid">

        <!-- Mobile Sidebar Button -->

        <button class="btn btn-outline-success d-lg-none me-3" id="sidebarToggle">

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

            <!-- Company Dropdown -->

            <div class="dropdown">

                <a href="#"

                    class="d-flex align-items-center text-decoration-none"

                    data-bs-toggle="dropdown">

                    <?php

                    if (
                        !empty($company['company_logo']) &&
                        $company['company_logo'] != "default-company.png"
                    ) {

                        $logo = "../assets/uploads/company/" . $company['company_logo'];
                    } else {

                        $logo = "../assets/images/default-company.png";
                    }

                    ?>

                    <img
                        src="<?php echo $logo; ?>"
                        class="rounded-circle border"
                        width="40"
                        height="40"
                        alt="Company Logo">

                    <div class="ms-2 d-none d-md-block">

                        <h6 class="mb-0 fw-semibold">

                            Welcome

                        </h6>

                        <small class="text-muted">

                            <?php echo htmlspecialchars($company['company_name']); ?>

                        </small>

                    </div>

                    <i class="fas fa-chevron-down ms-2 text-secondary"></i>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow">

                    <li>

                        <a class="dropdown-item" href="../company/profile.php">

                            <i class="fas fa-building me-2"></i>

                            Company Profile

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item" href="../company/edit_profile.php">

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