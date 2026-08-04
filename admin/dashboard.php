<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* Admin Details */

$admin_id = $_SESSION['admin_id'];

$admin = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT * FROM admins WHERE id='$admin_id' LIMIT 1"
    )

);

/* ===========================
   Dashboard Statistics
=========================== */

$totalStudents = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM students"
    )

)['total'];

$totalCompanies = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM companies"
    )

)['total'];

$totalJobs = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM jobs"
    )

)['total'];

$totalApplications = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM applications"
    )

)['total'];

$pendingApplications = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Pending'"
    )

)['total'];

$acceptedApplications = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Accepted'"
    )

)['total'];

$rejectedApplications = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Rejected'"
    )

)['total'];

$shortlistedApplications = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE status='Shortlisted'"
    )

)['total'];

/* ===========================
   Recent Students
=========================== */

$recentStudents = mysqli_query(

    $conn,

    "SELECT
        id,
        full_name,
        email,
        course,
        profile_photo
     FROM students
     ORDER BY id DESC
     LIMIT 5"

);

/* ===========================
   Recent Companies
=========================== */

$recentCompanies = mysqli_query(

    $conn,

    "SELECT
        id,
        company_name,
        email,
        industry,
        company_logo
     FROM companies
     ORDER BY id DESC
     LIMIT 5"

);

/* ===========================
   Recent Jobs
=========================== */

$recentJobs = mysqli_query(

    $conn,

    "SELECT

        j.id,
        j.job_title,
        j.job_type,
        j.status,

        c.company_name

     FROM jobs j

     INNER JOIN companies c
     ON j.company_id=c.id

     ORDER BY j.id DESC

     LIMIT 5"

);

$page_title = "Dashboard";
$body_class = "admin-theme";

include('../includes/header.php');
include('../includes/sidebar_admin.php');
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<div class="main-content">

    <?php include('../includes/topbar_admin.php'); ?>

    <div class="container-fluid py-4">

        <!-- ===========================
Dashboard Cards
=========================== -->

        <div class="row">

            <!-- Total Students -->

            

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

                            <h3><?php echo $pendingApplications; ?></h3>

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

                            <h3><?php echo $shortlistedApplications; ?></h3>

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

                            <h3><?php echo $acceptedApplications; ?></h3>

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

                            <h3><?php echo $rejectedApplications; ?></h3>

                        </div>

                        <i class="fas fa-circle-xmark"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- ==========================================================
Recent Students
========================================================== -->

        <div class="row">

            <!-- Recent Students -->

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm">

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

                                <thead>

                                    <tr>

                                        <th>Photo</th>

                                        <th>Name</th>

                                        <th>Course</th>

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
                                                    width="50"
                                                    height="50"
                                                    class="rounded-circle border"
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

            <!-- Recent Companies -->

            <div class="col-lg-6 mb-4">

                <div class="card shadow-sm">

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

                                <thead>

                                    <tr>

                                        <th>Logo</th>

                                        <th>Company</th>

                                        <th>Industry</th>

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
                                                    width="50"
                                                    height="50"
                                                    class="rounded-circle border"
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

        <!-- ==========================================================
Recent Jobs + Quick Actions
========================================================== -->

        <div class="row">

            <!-- Recent Jobs -->

            <div class="col-xl-8 col-lg-7">

                <div class="card shadow-sm">

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

                                <thead>

                                    <tr>

                                        <th>ID</th>

                                        <th>Job Title</th>

                                        <th>Company</th>

                                        <th>Type</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    if (mysqli_num_rows($recentJobs) > 0) {

                                        while ($job = mysqli_fetch_assoc($recentJobs)) {

                                    ?>

                                            <tr>

                                                <td>

                                                    <?php echo $job['id']; ?>

                                                </td>

                                                <td>

                                                    <?php echo htmlspecialchars($job['job_title']); ?>

                                                </td>

                                                <td>

                                                    <?php echo htmlspecialchars($job['company_name']); ?>

                                                </td>

                                                <td>

                                                    <?php echo htmlspecialchars($job['job_type']); ?>

                                                </td>

                                                <td>

                                                    <?php

                                                    if ($job['status'] == "Active") {

                                                        echo '<span class="badge bg-success">Active</span>';
                                                    } else {

                                                        echo '<span class="badge bg-danger">Inactive</span>';
                                                    }

                                                    ?>

                                                </td>

                                            </tr>

                                    <?php

                                        }
                                    } else {

                                        echo '

                                <tr>

                                    <td colspan="5" class="text-center">

                                        No Jobs Found

                                    </td>

                                </tr>

                                ';
                                    }

                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Quick Actions -->

            <div class="col-xl-4 col-lg-5">

                <div class="card shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Quick Actions

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-grid gap-3">

                            <a
                                href="manage_students.php"
                                class="btn btn-primary">

                                <i class="fas fa-user-graduate me-2"></i>

                                Manage Students

                            </a>

                            <a
                                href="manage_companies.php"
                                class="btn btn-success">

                                <i class="fas fa-building me-2"></i>

                                Manage Companies

                            </a>

                            <a
                                href="manage_jobs.php"
                                class="btn btn-warning text-dark">

                                <i class="fas fa-briefcase me-2"></i>

                                Manage Jobs

                            </a>

                            <a
                                href="manage_applications.php"
                                class="btn btn-info text-white">

                                <i class="fas fa-file-alt me-2"></i>

                                Manage Applications

                            </a>

                            <a
                                href="reports.php"
                                class="btn btn-dark">

                                <i class="fas fa-chart-bar me-2"></i>

                                Reports

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php include('../includes/footer_admin.php'); ?>
</div>