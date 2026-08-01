<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================
   Admin Details
=========================== */
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

            j.job_title LIKE '%$search%'

            OR c.company_name LIKE '%$search%'

            OR j.category LIKE '%$search%'

            OR j.city LIKE '%$search%'

            OR j.state LIKE '%$search%'";
    }
}

/* ===========================
   Total Jobs
=========================== */

$totalQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM jobs j

     INNER JOIN companies c
     ON j.company_id=c.id

     $where"

);

$totalJobs = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalJobs / $limit);

/* ===========================
   Job List
=========================== */

$jobs = mysqli_query(

    $conn,

    "SELECT

        j.*,

        c.company_name,
        c.company_logo

     FROM jobs j

     INNER JOIN companies c
     ON j.company_id=c.id

     $where

     ORDER BY j.id DESC

     LIMIT $offset,$limit"

);

$page_title = "Manage Jobs";
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

        <!-- ===========================================
     Page Header
=========================================== -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <p class="text-muted mb-0">

                    <strong>

                        <p class="text-muted">

                            View, Search, Edit And Manage All Company Job Posts.

                        </p>

                    </strong>

                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Jobs :
                    <?php echo $totalJobs; ?>

                </span>

            </div>

        </div>

        <!-- ===========================================
     Search Card
=========================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3">

                        <div class="col-md-10">

                            <input
                                type="text"
                                name="search"
                                id="jobSearch"
                                class="form-control"
                                placeholder="Search By Job Title, Company, Category Or Location..."
                                value="<?php echo htmlspecialchars($search); ?>">

                        </div>

                        <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100">

                                <i class="fas fa-search me-2"></i>

                                Search

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        <!-- ===========================================
     Jobs Table
=========================================== -->

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="jobsTable">

                        <thead class="table-primary">

                            <tr>

                                <th width="80">

                                    Logo

                                </th>

                                <th>

                                    Company

                                </th>

                                <th>

                                    Job Title

                                </th>

                                <th>

                                    Category

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

                            if (mysqli_num_rows($jobs) > 0) {

                                while ($job = mysqli_fetch_assoc($jobs)) {

                                    if (
                                        !empty($job['company_logo']) &&
                                        $job['company_logo'] != "default_company.png"
                                    ) {

                                        $logo = "../assets/uploads/company/" . $job['company_logo'];
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

                                        <!-- Company -->

                                        <td>

                                            <h6 class="mb-1 fw-bold">

                                                <?php echo htmlspecialchars($job['company_name']); ?>

                                            </h6>

                                        </td>

                                        <!-- Job Title -->

                                        <td>

                                            <strong>

                                                <?php echo htmlspecialchars($job['job_title']); ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                <?php echo htmlspecialchars($job['job_type']); ?>

                                            </small>

                                        </td>

                                        <!-- Category -->

                                        <td>

                                            <?php echo htmlspecialchars($job['category']); ?>

                                        </td>

                                        <!-- Location -->

                                        <td>

                                            <?php echo htmlspecialchars($job['city']); ?>,

                                            <?php echo htmlspecialchars($job['state']); ?>

                                        </td>

                                        <!-- Status -->

                                        <td>

                                            <?php

                                            if ($job['status'] == "Active") {

                                                echo '<span class="badge bg-success">Active</span>';
                                            } else {

                                                echo '<span class="badge bg-danger">Closed</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- Actions -->

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm mb-1 viewJobBtn"
                                                data-bs-target="#job<?php echo $job['id']; ?>">

                                                <i class="fas fa-eye"></i>

                                                <span class="btn-text">

                                                    View

                                                </span>

                                            </button>

                                            <a
                                                href="edit_job.php?id=<?php echo $job['id']; ?>"
                                                class="btn btn-warning btn-sm mb-1">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <button
                                                class="btn btn-danger btn-sm mb-1 deleteJobBtn"
                                                data-id="<?php echo $job['id']; ?>">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td colspan="7" class="border-0 p-0">

                                            <div
                                                class="collapse mt-2"
                                                id="job<?php echo $job['id']; ?>">

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

                                                                    <?php echo htmlspecialchars($job['company_name']); ?>

                                                                </h5>

                                                                <p class="text-muted">

                                                                    <?php echo htmlspecialchars($job['job_title']); ?>

                                                                </p>

                                                            </div>

                                                            <!-- Right -->

                                                            <div class="col-lg-9">

                                                                <div class="row">

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Category</strong><br>

                                                                        <?php echo htmlspecialchars($job['category']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Job Type</strong><br>

                                                                        <?php echo htmlspecialchars($job['job_type']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Vacancy</strong><br>

                                                                        <?php echo htmlspecialchars($job['vacancy']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Experience</strong><br>

                                                                        <?php echo htmlspecialchars($job['experience']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Qualification</strong><br>

                                                                        <?php echo htmlspecialchars($job['qualification']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Salary</strong><br>

                                                                        <span class="badge bg-success">

                                                                            <?php echo htmlspecialchars($job['salary']); ?>

                                                                        </span>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>State</strong><br>

                                                                        <?php echo htmlspecialchars($job['state']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>City</strong><br>

                                                                        <?php echo htmlspecialchars($job['city']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Last Date</strong><br>

                                                                        <?php echo date("d M Y", strtotime($job['last_date'])); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Status</strong><br>

                                                                        <?php if ($job['status'] == "Active") { ?>

                                                                            <span class="badge bg-success">

                                                                                Active

                                                                            </span>

                                                                        <?php } else { ?>

                                                                            <span class="badge bg-danger">

                                                                                Closed

                                                                            </span>

                                                                        <?php } ?>

                                                                    </div>

                                                                </div>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Required Skills

                                                                </h6>

                                                                <?php

                                                                $skills = explode(",", $job['skills']);

                                                                foreach ($skills as $skill) {

                                                                ?>

                                                                    <span class="badge bg-primary me-2 mb-2 p-2">

                                                                        <?php echo trim($skill); ?>

                                                                    </span>

                                                                <?php } ?>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Job Description

                                                                </h6>

                                                                <p class="text-muted">

                                                                    <?php echo nl2br(htmlspecialchars($job['job_description'])); ?>

                                                                </p>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Responsibilities

                                                                </h6>

                                                                <p class="text-muted">

                                                                    <?php

                                                                    echo !empty($job['responsibilities'])
                                                                        ? nl2br(htmlspecialchars($job['responsibilities']))
                                                                        : "Not Available";

                                                                    ?>

                                                                </p>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Benefits

                                                                </h6>

                                                                <p class="text-muted">

                                                                    <?php

                                                                    echo !empty($job['benefits'])
                                                                        ? nl2br(htmlspecialchars($job['benefits']))
                                                                        : "Not Available";

                                                                    ?>

                                                                </p>

                                                                <hr>

                                                                <div class="d-flex justify-content-between align-items-center">

                                                                    <small class="text-muted">

                                                                        <i class="fas fa-calendar-alt me-1"></i>

                                                                        Posted On :

                                                                        <?php echo date("d M Y", strtotime($job['created_at'])); ?>

                                                                    </small>

                                                                </div>

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

                                    <td colspan="7" class="text-center py-5">

                                        <i class="fas fa-briefcase fa-4x text-secondary mb-3"></i>

                                        <h5>

                                            No Jobs Found

                                        </h5>

                                        <p class="text-muted">

                                            No Job Records Available.

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

                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

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

        /* ===============================
           View / Hide Details
        =============================== */

        $(".viewJobBtn").click(function() {

            var button = $(this);

            var target = $(button.data("bs-target"));

            target.collapse("toggle");

            target.off("shown.bs.collapse hidden.bs.collapse");

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

        /* ===============================
           Live Search
        =============================== */

        $("#jobSearch").on("keyup", function() {

            var value = $(this).val().toLowerCase();

            $("#jobsTable tbody tr").filter(function() {

                $(this).toggle(

                    $(this).text().toLowerCase().indexOf(value) > -1

                );

            });

        });

        /* ===============================
           Delete Job
        =============================== */

        $(".deleteJobBtn").click(function() {

            var id = $(this).data("id");

            Swal.fire({

                title: "Delete Job?",

                text: "This Job And All Applications Will Be Deleted.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#dc3545",

                cancelButtonColor: "#6c757d",

                confirmButtonText: "Yes, Delete",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = "delete_job.php?id=" + id;

                }

            });

        });

        /* ===============================
           Auto Hide Alert
        =============================== */

        setTimeout(function() {

            $(".alert").fadeOut();

        }, 3000);

    });
</script>