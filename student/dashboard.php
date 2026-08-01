<?php
require_once('../includes/db.php');
/** @var mysqli $conn */
require_once('../includes/session.php');
checkStudent();

/* Dashboard Statistics */
$totalJobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs"))['total'];

$totalApplications = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications WHERE student_id=" . $_SESSION['student_id'])
)['total'];

$student = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM students WHERE id=" . $_SESSION['student_id'])
);

$recentJobs = mysqli_query($conn, "
SELECT *
FROM jobs
ORDER BY id DESC
LIMIT 5
");

if (
    !empty($student['profile_photo']) &&
    $student['profile_photo'] != "default-user.png"
) {

    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
} else {

    $photo = "../assets/images/default-user.png";
}

$page_title = "Student Dashboard";
$body_class = "student-theme";

include('../includes/header.php');
include('../includes/sidebar_student.php');
?>

<div class="main-content">

    <?php include('../includes/topbar.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="row">

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

                <div class="col-md-4">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6>My Applications</h6>

                                <h3><?php echo $totalApplications; ?></h3>

                            </div>

                            <i class="fas fa-file-alt"></i>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h6>Welcome</h6>

                                <h3><?php echo htmlspecialchars($student['full_name']); ?></h3>

                            </div>

                            <i class="fas fa-user-graduate"></i>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row mt-4">

                <div class="col-xl-8 col-lg-7">
                    <div class="card">

                        <div class="card-header">

                            Recent Jobs

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

                                                    <td><?php echo htmlspecialchars($job['last_date']); ?></td>

                                                </tr>

                                            <?php
                                            }
                                        } else {
                                            ?>

                                            <tr>

                                                <td colspan="4" class="text-center">

                                                    No Jobs Available

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card">

                        <div class="card-header">

                            Profile Summary

                        </div>

                        <div class="card-body text-center">

                            <img
                                src=" <?php echo $photo; ?>"
                                class="profile-image mb-3"
                                alt="Profile">

                            <h5>

                                <?php echo htmlspecialchars($student['full_name']); ?>

                            </h5>

                            <p>

                                <?php echo htmlspecialchars($student['email']); ?>

                            </p>

                            <p>

                                <?php echo htmlspecialchars($student['phone']); ?>

                            </p>

                            <a
                                href="profile.php"
                                class="btn btn-primary">

                                View Profile

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php include('../includes/footer_student.php'); ?>

</div>