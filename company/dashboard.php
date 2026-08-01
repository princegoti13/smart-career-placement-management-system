<?php
require_once('../includes/db.php');
require_once('../includes/session.php');
checkCompany();
/** @var mysqli $conn */

$company_id = $_SESSION['company_id'];

/* Dashboard Statistics */

$totalJobs = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM jobs WHERE company_id=$company_id")
)['total'];

$totalApplications = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total
        FROM applications a
        JOIN jobs j ON a.job_id=j.id
        WHERE j.company_id=$company_id
    ")
)['total'];

$pendingApplications = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) total
        FROM applications a
        JOIN jobs j ON a.job_id=j.id
        WHERE j.company_id=$company_id
        AND a.status='Pending'
    ")
)['total'];

$recentJobs = mysqli_query($conn, "
SELECT *
FROM jobs
WHERE company_id='$company_id'
ORDER BY id DESC
LIMIT 5
");


$recentApplications = mysqli_query($conn, "
SELECT
a.*,
s.full_name,
j.job_title
FROM applications a
INNER JOIN students s ON a.student_id=s.id
INNER JOIN jobs j ON a.job_id=j.id
WHERE j.company_id='$company_id'
ORDER BY a.application_date DESC
LIMIT 5
");

$company = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT *
        FROM companies
        WHERE id=$company_id
    ")
);

$page_title = "Company Dashboard";

$body_class = "company-theme";

include('../includes/header.php');
include('../includes/sidebar_company.php');
?>

<link rel="stylesheet" href="../assets/css/company.css">

<div class="main-content">

    <?php include('../includes/topbar_company.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="row">

                <!-- Total Jobs -->

                <div class="col-md-4">

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

                <div class="col-md-4">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6>Total Applications</h6>

                                <h3><?php echo $totalApplications; ?></h3>

                            </div>

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

                <!-- Pending Applications -->

                <div class="col-md-4">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6>Pending Applications</h6>

                                <h3><?php echo $pendingApplications; ?></h3>

                            </div>

                            <i class="fas fa-clock"></i>

                        </div>

                    </div>

                </div>

            </div>



            <div class="row mt-4">

                <!-- Left Side Start -->

                <div class="col-xl-8 col-lg-7">

                    <!-- Recent Jobs -->

                    <div class="card">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <span>Recent Jobs</span>

                            <a href="manage_jobs.php" class="btn btn-sm btn-success">

                                View All

                            </a>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-hover">

                                    <thead>

                                        <tr>

                                            <th>ID</th>

                                            <th>Job Title</th>

                                            <th>Location</th>

                                            <th>Deadline</th>

                                            <th>Status</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        if (mysqli_num_rows($recentJobs) > 0) {

                                            while ($job = mysqli_fetch_assoc($recentJobs)) {

                                        ?>

                                                <tr>

                                                    <td><?php echo $job['id']; ?></td>

                                                    <td><?php echo htmlspecialchars($job['job_title']); ?></td>

                                                    <td><?php echo htmlspecialchars($job['city']); ?></td>

                                                    <td><?php echo date("d M Y", strtotime($job['last_date'])); ?></td>

                                                    <td>

                                                        <?php if ($job['status'] == "Active") { ?>

                                                            <span class="badge bg-success">

                                                                Active

                                                            </span>

                                                        <?php } else { ?>

                                                            <span class="badge bg-danger">

                                                                Inactive

                                                            </span>

                                                        <?php } ?>

                                                    </td>

                                                </tr>

                                        <?php

                                            }
                                        } else {

                                            echo '<tr>

                                        <td colspan="5" class="text-center">

                                            No Jobs Posted

                                        </td>

                                    </tr>';
                                        }

                                        ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <!-- Recent Applications -->

                    <div class="card mt-4">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <span>Recent Applications</span>

                            <a href="view_applications.php" class="btn btn-sm btn-primary">

                                View All

                            </a>

                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-hover">

                                    <thead>

                                        <tr>

                                            <th>Student</th>

                                            <th>Job</th>

                                            <th>Applied</th>

                                            <th>Status</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        if (mysqli_num_rows($recentApplications) > 0) {

                                            while ($app = mysqli_fetch_assoc($recentApplications)) {

                                        ?>

                                                <tr>

                                                    <td>

                                                        <?php echo htmlspecialchars($app['full_name']); ?>

                                                    </td>

                                                    <td>

                                                        <?php echo htmlspecialchars($app['job_title']); ?>

                                                    </td>

                                                    <td>

                                                        <?php echo date("d M Y", strtotime($app['application_date'])); ?>

                                                    </td>

                                                    <td>

                                                        <?php

                                                        switch ($app['status']) {

                                                            case "Pending":

                                                                echo '<span class="badge bg-warning">Pending</span>';

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

                                                            default:

                                                                echo '<span class="badge bg-secondary">' .
                                                                    htmlspecialchars($app['status']) .
                                                                    '</span>';
                                                        }

                                                        ?>

                                                    </td>

                                                </tr>

                                        <?php

                                            }
                                        } else {

                                            echo '

                                <tr>

                                    <td colspan="4" class="text-center">

                                        No Applications Found

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

                <!-- Right Side Start -->

                <div class="col-xl-4 col-lg-5">

                    <!-- Company Summary -->

                    <div class="card">

                        <div class="card-header">

                            Company Summary

                        </div>

                        <div class="card-body text-center">

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
                                class="profile-image mb-3"
                                alt="Company Logo">

                            <h5>

                                <?php echo htmlspecialchars($company['company_name']); ?>

                            </h5>

                            <p>

                                <?php echo htmlspecialchars($company['email']); ?>

                            </p>

                            <p>

                                <?php echo htmlspecialchars($company['phone']); ?>

                            </p>

                            <a
                                href="profile.php"
                                class="btn btn-success">

                                View Profile

                            </a>

                        </div>

                    </div>

                    <!-- Quick Actions -->

                    <div class="card mt-4">

                        <div class="card-header">

                            Quick Actions

                        </div>

                        <div class="card-body">

                            <div class="d-grid gap-3">

                                <a href="post_job.php"
                                    class="btn btn-success">

                                    <i class="fas fa-plus-circle me-2"></i>

                                    Post New Job

                                </a>

                                <a href="manage_jobs.php"
                                    class="btn btn-primary">

                                    <i class="fas fa-briefcase me-2"></i>

                                    Manage Jobs

                                </a>

                                <a href="view_applications.php"
                                    class="btn btn-warning text-dark">

                                    <i class="fas fa-user-graduate me-2"></i>

                                    View Applications

                                </a>

                                <a href="edit_profile.php"
                                    class="btn btn-info text-white">

                                    <i class="fas fa-user-edit me-2"></i>

                                    Edit Company Profile

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right Side End -->

            </div>

        </div>

    </div>
    <?php include('../includes/footer_company.php'); ?>
</div>