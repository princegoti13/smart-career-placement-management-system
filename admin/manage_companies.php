<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* Admin Details */
$error = "";
$success = "";
$admin_id = $_SESSION['admin_id'];

$admin = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT *
         FROM admins
         WHERE id='$admin_id'
         LIMIT 1"
    )

);

/* ===========================
   Pagination
=========================== */

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page <= 0) {

    $page = 1;
}

$offset = ($page - 1) * $limit;

/* ===========================
   Search
=========================== */

$search = "";

$where = "";

if (isset($_GET['search'])) {

    $search = cleanInput($_GET['search']);

    if (!empty($search)) {

        $where = "WHERE

            company_name LIKE '%$search%'

            OR email LIKE '%$search%'

            OR phone LIKE '%$search%'

            OR industry LIKE '%$search%'

            OR city LIKE '%$search%'

            OR state LIKE '%$search%'";
    }
}

/* ===========================
   Total Companies
=========================== */

$totalQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM companies

     $where"

);

$totalCompanies = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalCompanies / $limit);

/* ===========================
   Company List
=========================== */

$companies = mysqli_query(

    $conn,

    "SELECT *

     FROM companies

     $where

     ORDER BY id DESC

     LIMIT $offset,$limit"

);

$page_title = "Manage Companies";
$body_class = "admin-theme";

include('../includes/header.php');
include('../includes/sidebar_admin.php');
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<div class="main-content">

    <?php include('../includes/topbar_admin.php'); ?>

    <div class="container-fluid py-4">

        <?php if ($success != "") { ?>

            <div class="alert alert-success">

                <i class="fas fa-check-circle me-2"></i>

                <?php echo $success; ?>

            </div>

        <?php } ?>
        <?php if ($error != "") { ?>

            <div class="alert alert-danger">

                <i class="fas fa-times-circle me-2"></i>

                <?php echo $error; ?>

            </div>

        <?php } ?>

        <!-- ===========================
     Page Header
=========================== -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <p class="text-muted mb-0">

                    <strong>

                        <p class="text-muted">

                            View, Search, Edit And Manage All Registered Companies.

                        </p>

                    </strong>

                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Companies :
                    <?php echo $totalCompanies; ?>

                </span>

            </div>

        </div>

        <!-- ===========================
     Search Card
=========================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="search"
                                id="companySearch"
                                class="form-control"
                                placeholder="Search By Company Name, Email, Phone, Industry Or Location..."
                                value="<?php echo htmlspecialchars($search); ?>">

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- ===========================
     Company Table
=========================== -->

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="companiesTable">

                        <thead class="table-primary">

                            <tr>

                                <th width="80">

                                    Logo

                                </th>

                                <th>

                                    Company

                                </th>

                                <th>

                                    Industry

                                </th>

                                <th>

                                    Location

                                </th>

                                <th>

                                    Status

                                </th>

                                <th class="text-center">

                                    Actions

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($companies) > 0) {

                                while ($company = mysqli_fetch_assoc($companies)) {

                                    if (
                                        !empty($company['company_logo']) &&
                                        $company['company_logo'] != "default_company.png"
                                    ) {

                                        $logo = "../assets/uploads/company/" . $company['company_logo'];
                                    } else {

                                        $logo = "../assets/images/default_company.png";
                                    }

                            ?>

                                    <tr>

                                        <!-- Company Logo -->

                                        <td>
    
                                            <img
                                                src="<?php echo $logo; ?>"
                                                class="rounded-circle border py-2"
                                                width="60"
                                                height="55"
                                                style="object-fit:cover;">

                                        </td>

                                        <!-- Company Information -->

                                        <td>

                                            <h6 class="mb-1 fw-bold">

                                                <?php echo htmlspecialchars($company['company_name']); ?>

                                            </h6>

                                            <small class="text-muted d-block">

                                                <i class="fas fa-envelope me-1"></i>

                                                <?php echo htmlspecialchars($company['email']); ?>

                                            </small>

                                            <small class="text-muted">

                                                <i class="fas fa-phone me-1"></i>

                                                <?php echo htmlspecialchars($company['phone']); ?>

                                            </small>

                                        </td>

                                        <!-- Industry -->

                                        <td>

                                            <?php echo htmlspecialchars($company['industry']); ?>

                                        </td>

                                        <!-- Location -->

                                        <td>

                                            <?php echo htmlspecialchars($company['city']); ?>,

                                            <?php echo htmlspecialchars($company['state']); ?>

                                        </td>

                                        <!-- Status -->

                                        <td>

                                            <?php

                                            if (($company['account_status'] ?? '') == "Active") {

                                                echo '<span class="badge bg-success">Active</span>';
                                            } else {

                                                echo '<span class="badge bg-danger">Inactive</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- Actions -->

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm mb-1 viewCompanyBtn"
                                                data-bs-target="#company<?php echo $company['id']; ?>">

                                                <i class="fas fa-eye"></i>

                                                <span class="btn-text">

                                                    View

                                                </span>

                                            </button>

                                            <a
                                                href="edit_company.php?id=<?php echo $company['id']; ?>"
                                                class="btn btn-warning btn-sm mb-1">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <button
                                                class="btn btn-danger btn-sm mb-1 deleteCompanyBtn"
                                                data-id="<?php echo $company['id']; ?>">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td colspan="6" class="border-0 p-0">

                                            <div
                                                class="collapse mt-2"
                                                id="company<?php echo $company['id']; ?>">

                                                <div class="card border-0 shadow-sm">

                                                    <div class="card-body">

                                                        <div class="row">

                                                            <!-- Left -->

                                                            <div class="col-lg-3 text-center">

                                                                <img
                                                                    src="<?php echo $logo; ?>"
                                                                    class="profile-image mb-3"
                                                                    alt="Company Logo">

                                                                <h5>

                                                                    <?php echo htmlspecialchars($company['company_name']); ?>

                                                                </h5>

                                                                <p class="text-muted">

                                                                    <?php echo htmlspecialchars($company['email']); ?>

                                                                </p>

                                                            </div>

                                                            <!-- Right -->

                                                            <div class="col-lg-9">

                                                                <div class="row">

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Phone</strong><br>

                                                                        <?php echo htmlspecialchars($company['phone']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Industry</strong><br>

                                                                        <?php echo htmlspecialchars($company['industry']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Website</strong><br>

                                                                        <?php

                                                                        if (!empty($company['website'])) {

                                                                        ?>

                                                                            <a
                                                                                href="<?php echo htmlspecialchars($company['website']); ?>"
                                                                                target="_blank">

                                                                                <?php echo htmlspecialchars($company['website']); ?>

                                                                            </a>

                                                                        <?php

                                                                        } else {

                                                                            echo "Not Updated";
                                                                        }

                                                                        ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Status</strong><br>

                                                                        <?php

                                                                        if (($company['account_status'] ?? '') == "Active") {

                                                                            echo '<span class="badge bg-success">Active</span>';
                                                                        } else {

                                                                            echo '<span class="badge bg-danger">Inactive</span>';
                                                                        }

                                                                        ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>State</strong><br>

                                                                        <?php echo htmlspecialchars($company['state']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>City</strong><br>

                                                                        <?php echo htmlspecialchars($company['city']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Pincode</strong><br>

                                                                        <?php echo htmlspecialchars($company['pincode']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Address</strong><br>

                                                                        <?php echo htmlspecialchars($company['address']); ?>

                                                                    </div>

                                                                </div>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    About Company

                                                                </h6>

                                                                <p class="text-muted mb-4">

                                                                    <?php

                                                                    echo !empty($company['about_company'])
                                                                        ? nl2br(htmlspecialchars($company['about_company']))
                                                                        : "Not Updated";

                                                                    ?>

                                                                </p>

                                                                <!-- <div class="d-flex justify-content-end">

                                                                    <a
                                                                        href="edit_company.php?id=<?php echo $company['id']; ?>"
                                                                        class="btn btn-warning">

                                                                        <i class="fas fa-edit me-2"></i>

                                                                        Edit Company

                                                                    </a>

                                                                </div> -->

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                <?php

                                }
                            } else {

                                ?>

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <i class="fas fa-building fa-4x text-secondary mb-3"></i>

                                        <h5>

                                            No Companies Found

                                        </h5>

                                        <p class="text-muted">

                                            No Company Records Available.

                                        </p>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- ===========================================
Pagination
=========================================== -->

        <?php if ($totalPages > 1) { ?>

            <nav class="mt-4">

                <ul class="pagination justify-content-center">

                    <?php if ($page > 1) { ?>

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">

                                Previous

                            </a>

                        </li>

                    <?php } ?>

                    <?php

                    for ($i = 1; $i <= $totalPages; $i++) {

                    ?>

                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">

                            <a
                                class="page-link"
                                href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">

                                <?php echo $i; ?>

                            </a>

                        </li>

                    <?php } ?>

                    <?php if ($page < $totalPages) { ?>

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">

                                Next

                            </a>

                        </li>

                    <?php } ?>

                </ul>

            </nav>

        <?php } ?>

    </div>
    <?php include('../includes/footer_admin.php'); ?>
</div>



<script>
    $(document).ready(function() {

        /* ===========================
           View / Hide Company Details
        =========================== */

        $(document).on("click", ".viewCompanyBtn", function() {

            var button = $(this);

            var target = $(button.data("bs-target"));

            target.collapse("toggle");

            target.on("shown.bs.collapse", function() {

                button
                    .removeClass("btn-info")
                    .addClass("btn-secondary");

                button.find(".btn-text").text("Hide");

            });

            target.on("hidden.bs.collapse", function() {

                button
                    .removeClass("btn-secondary")
                    .addClass("btn-info");

                button.find(".btn-text").text("View");

            });

        });

        /* ===========================
           Live Search
        =========================== */

        $("#companySearch").on("keyup", function() {

            var value = $(this).val().toLowerCase();

            $("#companiesTable tbody tr").filter(function() {

                $(this).toggle(

                    $(this).text().toLowerCase().indexOf(value) > -1

                );

            });

        });

        /* ===========================
           Delete Company
        =========================== */

        $(document).on("click", ".deleteCompanyBtn", function() {

            var id = $(this).data("id");

            Swal.fire({

                title: "Delete Company?",

                text: "This Company And All Related Jobs & Applications Will Be Deleted.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#dc3545",

                cancelButtonColor: "#6c757d",

                confirmButtonText: "Yes, Delete",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = "delete_company.php?id=" + id;

                }

            });

        });

    });
</script>