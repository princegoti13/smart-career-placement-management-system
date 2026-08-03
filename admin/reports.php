<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   Admin Details
=========================================== */

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

/* ===========================================
   Dashboard Counts
=========================================== */

$totalStudents = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM students"

    )

)['total'];

$totalCompanies = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM companies"

    )

)['total'];

$totalJobs = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM jobs"

    )

)['total'];

$totalApplications = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM applications"

    )

)['total'];

/* ===========================================
   Application Status
=========================================== */

$pending = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Pending'"

    )

)['total'];

$shortlisted = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Shortlisted'"

    )

)['total'];

$accepted = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Accepted'"

    )

)['total'];

$rejected = mysqli_fetch_assoc(

    mysqli_query(

        $conn,

        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Rejected'"

    )

)['total'];

/* ===========================================
   Recent Students
=========================================== */

$recentStudents = mysqli_query(

    $conn,

    "SELECT *
     FROM students
     ORDER BY id DESC
     LIMIT 5"

);

/* ===========================================
   Recent Companies
=========================================== */

$recentCompanies = mysqli_query(

    $conn,

    "SELECT *
     FROM companies
     ORDER BY id DESC
     LIMIT 5"

);

/* ===========================================
   Recent Jobs
=========================================== */

$recentJobs = mysqli_query(

    $conn,

    "SELECT

        j.*,
        c.company_name

     FROM jobs j

     INNER JOIN companies c
     ON j.company_id=c.id

     ORDER BY j.id DESC

     LIMIT 5"

);

/* ===========================================
   Recent Applications
=========================================== */

$recentApplications = mysqli_query(

    $conn,

    "SELECT

        a.*,

        s.full_name,

        j.job_title,

        c.company_name

     FROM applications a

     INNER JOIN students s
     ON a.student_id=s.id

     INNER JOIN jobs j
     ON a.job_id=j.id

     INNER JOIN companies c
     ON j.company_id=c.id

     ORDER BY a.application_date DESC

     LIMIT 5"

);

$page_title = "Reports";
$body_class = "admin-theme";

include('../includes/header.php');
include('../includes/sidebar_admin.php');
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<div class="main-content">

    <?php include('../includes/topbar_admin.php'); ?>

    <div class="container-fluid py-4">

        <!-- ===========================================
     Dashboard Statistics
=========================================== -->

        <div class="row">

            <!-- Total Students -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Students</h6>

                            <h3><?php echo $totalStudents; ?></h3>

                        </div>

                        <i class="fas fa-user-graduate"></i>

                    </div>

                </div>

            </div>

            <!-- Total Companies -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Companies</h6>

                            <h3><?php echo $totalCompanies; ?></h3>

                        </div>

                        <i class="fas fa-building"></i>

                    </div>

                </div>

            </div>

            <!-- Total Jobs -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Jobs</h6>

                            <h3><?php echo $totalJobs; ?></h3>

                        </div>

                        <i class="fas fa-briefcase"></i>

                    </div>

                </div>

            </div>

            <!-- Total Applications -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Applications</h6>

                            <h3><?php echo $totalApplications; ?></h3>

                        </div>

                        <i class="fas fa-file-alt"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================
Application Status Cards
=========================== -->

        <div class="row">

            <!-- Pending -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Pending</h6>

                            <h3><?php echo $pending; ?></h3>

                        </div>

                        <i class="fas fa-clock"></i>

                    </div>

                </div>

            </div>

            <!-- Shortlisted -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Shortlisted</h6>

                            <h3><?php echo $shortlisted; ?></h3>

                        </div>

                        <i class="fas fa-list-check"></i>

                    </div>

                </div>

            </div>

            <!-- Accepted -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Accepted</h6>

                            <h3><?php echo $accepted; ?></h3>

                        </div>

                        <i class="fas fa-circle-check"></i>

                    </div>

                </div>

            </div>

            <!-- Rejected -->

            <div class="col-xl-3 col-md-6 mb-4">

                <div class="dashboard-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Rejected</h6>

                            <h3><?php echo $rejected; ?></h3>

                        </div>

                        <i class="fas fa-circle-xmark"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================================
     Recent Students & Companies
=========================================== -->

        <div class="row">

            <!-- ===========================================
         Recent Students
    =========================================== -->

            <div class="col-lg-7 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            Recent Students

                        </h5>

                        <a
                            href="manage_students.php"
                            class="btn btn-primary btn-sm">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-primary">

                                    <tr>

                                        <th width="80">

                                            Photo

                                        </th>

                                        <th>

                                            Student

                                        </th>

                                        <th>

                                            Course

                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    while ($student = mysqli_fetch_assoc($recentStudents)) {

                                        if (
                                            !empty($student['profile_photo']) &&
                                            $student['profile_photo'] != "default-user.png"
                                        ) {

                                            $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
                                        } else {

                                            $photo = "../assets/images/default-user.png";
                                        }

                                    ?>

                                        <tr>

                                            <td>

                                                <img
                                                    src="<?php echo $photo; ?>"
                                                    class="rounded-circle border"
                                                    width="60"
                                                    height="55"
                                                    style="object-fit:cover;">

                                            </td>

                                            <td>

                                                <strong>

                                                    <?php echo htmlspecialchars($student['full_name']); ?>

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    <?php echo htmlspecialchars($student['email']); ?>

                                                </small>

                                            </td>

                                            <td>

                                                <?php echo htmlspecialchars($student['course']); ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ===========================================
         Recent Companies
    =========================================== -->

            <div class="col-lg-5 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            Recent Companies

                        </h5>

                        <a
                            href="manage_companies.php"
                            class="btn btn-primary btn-sm">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

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

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    while ($company = mysqli_fetch_assoc($recentCompanies)) {

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

                                            <td>

                                                <img
                                                    src="<?php echo $logo; ?>"
                                                    class="rounded-circle border py-2"
                                                    width="60"
                                                    height="55"
                                                    style="object-fit:cover;">

                                            </td>

                                            <td>

                                                <strong>

                                                    <?php echo htmlspecialchars($company['company_name']); ?>

                                                </strong>

                                                <br>

                                                <small class="text-muted">

                                                    <?php echo htmlspecialchars($company['email']); ?>

                                                </small>

                                            </td>

                                            <td>

                                                <?php echo htmlspecialchars($company['industry']); ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================================
     Recent Jobs & Recent Applications
=========================================== -->

        <div class="row">

            <!-- Recent Jobs -->

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            Recent Jobs

                        </h5>

                        <a
                            href="manage_jobs.php"
                            class="btn btn-primary btn-sm">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-primary">

                                    <tr>

                                        <th>Company</th>

                                        <th>Job Title</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php while ($job = mysqli_fetch_assoc($recentJobs)) { ?>

                                        <tr>

                                            <td>

                                                <?php echo htmlspecialchars($job['company_name']); ?>

                                            </td>

                                            <td>

                                                <?php echo htmlspecialchars($job['job_title']); ?>

                                            </td>

                                            <td>

                                                <?php if ($job['status'] == "Active") { ?>

                                                    <span class="badge bg-success">

                                                        Active

                                                    </span>

                                                <?php } else { ?>

                                                    <span class="badge bg-danger">

                                                        Closed

                                                    </span>

                                                <?php } ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Recent Applications -->

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            Recent Applications

                        </h5>

                        <a
                            href="manage_applications.php"
                            class="btn btn-primary btn-sm">

                            View All

                        </a>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-primary">

                                    <tr>

                                        <th>Student</th>

                                        <th>Company</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php while ($app = mysqli_fetch_assoc($recentApplications)) { ?>

                                        <tr>

                                            <td>

                                                <?php echo htmlspecialchars($app['full_name']); ?>

                                            </td>

                                            <td>

                                                <?php echo htmlspecialchars($app['company_name']); ?>

                                            </td>

                                            <td>

                                                <?php

                                                switch ($app['status']) {

                                                    case "Pending":

                                                        echo '<span class="badge bg-warning text-dark">Pending</span>';

                                                        break;

                                                    case "Shortlisted":

                                                        echo '<span class="badge bg-primary">Shortlisted</span>';

                                                        break;

                                                    case "Accepted":

                                                        echo '<span class="badge bg-success">Accepted</span>';

                                                        break;

                                                    case "Rejected":

                                                        echo '<span class="badge bg-danger">Rejected</span>';

                                                        break;
                                                }

                                                ?>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Charts -->

        <div class="row">

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Application Status

                        </h5>

                    </div>

                    <div class="card-body text-center" style="height:350px;">

                        <canvas id="applicationChart"></canvas>

                    </div>

                </div>

            </div>

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <h5 class="mb-0">

                            System Overview

                        </h5>

                    </div>

                    <div class="card-body" style="height:350px;">

                        <canvas id="dashboardChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================================
     Quick Actions
=========================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Quick Actions

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="manage_students.php"
                            class="btn btn-primary w-100">

                            <i class="fas fa-user-graduate me-2"></i>

                            Manage Students

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="manage_companies.php"
                            class="btn btn-primary w-100">

                            <i class="fas fa-building me-2"></i>

                            Manage Companies

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="manage_jobs.php"
                            class="btn btn-primary w-100">

                            <i class="fas fa-briefcase me-2"></i>

                            Manage Jobs

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="manage_applications.php"
                            class="btn btn-primary w-100">

                            <i class="fas fa-file-alt me-2"></i>

                            Manage Applications

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================================
     Export Reports
=========================================== -->

        <div class="card shadow-sm border-0">

            <div class="card-header">

                <h5 class="mb-0">

                    Export Reports

                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted mb-4">

                    Download Reports In CSV Format.

                </p>

                <div class="row g-3">

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="export_students.php"
                            class="btn btn-outline-primary w-100">

                            <i class="fas fa-download me-2"></i>

                            Students Report

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="export_companies.php"
                            class="btn btn-outline-primary w-100">

                            <i class="fas fa-download me-2"></i>

                            Companies Report

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="export_jobs.php"
                            class="btn btn-outline-primary w-100">

                            <i class="fas fa-download me-2"></i>

                            Jobs Report

                        </a>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a
                            href="export_applications.php"
                            class="btn btn-outline-primary w-100">

                            <i class="fas fa-download me-2"></i>

                            Applications Report

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php include('../includes/footer_admin.php'); ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        setTimeout(function() {

            document.querySelectorAll(".alert").forEach(function(alert) {

                alert.style.transition = "0.5s";
                alert.style.opacity = "0";

                setTimeout(function() {

                    alert.remove();

                }, 500);

            });

        }, 3000);

    });

    // =========================
    // Application Pie Chart
    // =========================

    new Chart(document.getElementById("applicationChart"), {

        type: "pie",

        data: {

            labels: ["Pending", "Shortlisted", "Accepted", "Rejected"],

            datasets: [{

                data: [
                    <?php echo $pending; ?>,
                    <?php echo $shortlisted; ?>,
                    <?php echo $accepted; ?>,
                    <?php echo $rejected; ?>
                ],

                backgroundColor: [
                    "#ffc107",
                    "#6f42c1",
                    "#198754",
                    "#dc3545"
                ]

            }]

        },
        options: {

            responsive: true,

            maintainAspectRatio: false

        }

    });

    // =========================
    // Dashboard Bar Chart
    // =========================

    new Chart(document.getElementById("dashboardChart"), {

        type: "bar",

        data: {

            labels: [

                "Students",
                "Companies",
                "Jobs",
                "Applications"

            ],

            datasets: [{

                label: "Total",

                data: [

                    <?php echo $totalStudents; ?>,
                    <?php echo $totalCompanies; ?>,
                    <?php echo $totalJobs; ?>,
                    <?php echo $totalApplications; ?>

                ],

                backgroundColor: [

                    "#6f42c1",
                    "#0d6efd",
                    "#198754",
                    "#fd7e14"

                ]

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    });
</script>