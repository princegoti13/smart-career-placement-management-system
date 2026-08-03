<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

checkStudent();

$student = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT *
         FROM students
         WHERE id='" . $_SESSION['student_id'] . "'"
    )

);

$student_id = $_SESSION['student_id'];
$page_title = "My Applications";
$body_class = "student-theme";

$applications = mysqli_query($conn, "
SELECT

a.*,

j.job_title,
j.category,
j.job_type,
j.salary,
j.location,
j.skills,
j.eligibility,
j.job_description,
j.last_date,

c.company_name,
c.company_logo

FROM applications a

INNER JOIN jobs j
ON a.job_id=j.id

INNER JOIN companies c
ON j.company_id=c.id

WHERE a.student_id='$student_id'

ORDER BY a.application_date DESC
");

$totalApplications = mysqli_num_rows($applications);
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/sidebar_student.php'); ?>

<!-- Page Header -->
<div class="main-content">
    <?php include('../includes/topbar.php'); ?>

    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="fw-bold">Track Your Applications</h4>

                <p class="text-muted mb-0">
                    Check your application status and company updates.
                </p>

            </div>
        
            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Applications :
                    <?php echo $totalApplications; ?>

                </span>

            </div>

        </div>

        <!-- Search Card -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <input
                            type="text"
                            class="form-control"
                            id="searchApplication"
                            placeholder="Search By Job Title Or Company">

                    </div>

                    <div class="col-md-3">

                        <select class="form-select" id="statusFilter">

                            <option value="">All Status</option>

                            <option value="Pending">Pending</option>

                            <option value="Shortlisted">Shortlisted</option>

                            <option value="Rejected">Rejected</option>

                            <option value="Accepted">Accepted</option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

        <!-- Applications Card -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-briefcase text-primary me-2"></i>

                    Application History

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="70">Logo</th>

                                <th>Job Details</th>

                                <th>Company</th>

                                <th>Applied On</th>

                                <th>Status</th>

                                <th>Remark</th>

                            </tr>

                        </thead>

                        <tbody id="applicationTable">

                            <!-- Part 2 -->
                            <?php if (mysqli_num_rows($applications) > 0) { ?>

                                <?php while ($application = mysqli_fetch_assoc($applications)) { ?>

                                    <tr>

                                        <td>

                                            <?php if (!empty($application['company_logo']) && file_exists("../assets/uploads/company/" . $application['company_logo'])) { ?>

                                                <img src="../assets/uploads/company/<?php echo $application['company_logo']; ?>"
                                                    class="rounded"
                                                    width="55"
                                                    height="55"
                                                    style="object-fit:cover;">

                                            <?php } else { ?>

                                                <img src="../assets/images/company.png"
                                                    class="rounded"
                                                    width="55"
                                                    height="55">

                                            <?php } ?>

                                        </td>

                                        <td>

                                            <h6 class="fw-bold mb-1">

                                                <?php echo htmlspecialchars($application['job_title']); ?>

                                            </h6>

                                            <small class="text-muted d-block">

                                                <i class="fas fa-map-marker-alt text-danger"></i>

                                                <?php echo htmlspecialchars($application['location']); ?>

                                            </small>

                                            <small class="text-success fw-semibold d-block mt-1">

                                                ₹ <?php echo htmlspecialchars($application['salary']); ?>

                                            </small>

                                            <span class="badge bg-info mt-2">

                                                <?php echo htmlspecialchars($application['job_type']); ?>

                                            </span>

                                        </td>

                                        <td>

                                            <strong>

                                                <?php echo htmlspecialchars($application['company_name']); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?php echo date("d M Y", strtotime($application['application_date'])); ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($application['status'] == "Pending") {

                                                echo '<span class="badge bg-warning text-dark">Pending</span>';
                                            } elseif ($application['status'] == "Shortlisted") {

                                                echo '<span class="badge bg-info">Shortlisted</span>';
                                            } elseif ($application['status'] == "Accepted") {

                                                echo '<span class="badge bg-success">Accepted</span>';
                                            } elseif ($application['status'] == "Rejected") {

                                                echo '<span class="badge bg-danger">Rejected</span>';
                                            } else {

                                                echo '<span class="badge bg-secondary">' . $application['status'] . '</span>';
                                            }

                                            ?>

                                        </td>

                                        <td>

                                            <?php

                                            if (!empty($application['company_remark'])) {

                                                echo nl2br(htmlspecialchars($application['company_remark']));
                                            } else {

                                                echo '<span class="text-muted">No Remark</span>';
                                            }

                                            ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            <?php } else { ?>

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <i class="fas fa-folder-open fa-4x text-secondary mb-3"></i>

                                        <h5>No Applications Found</h5>

                                        <p class="text-muted mb-0">

                                            You have not applied for any jobs yet.

                                        </p>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
    <?php include('../includes/footer_student.php'); ?>
</div>



<script>
    $(document).ready(function() {

        function filterApplications() {

            var search = $("#searchApplication").val().toLowerCase();

            var status = $("#statusFilter").val().toLowerCase();

            $("#applicationTable tr").each(function() {

                var row = $(this);

                var text = row.text().toLowerCase();

                var rowStatus = row.find("td:eq(4)").text().toLowerCase();

                var matchSearch = text.indexOf(search) > -1;

                var matchStatus = (status == "") || (rowStatus.indexOf(status) > -1);

                if (matchSearch && matchStatus) {

                    row.show();

                } else {

                    row.hide();

                }

            });

        }

        $("#searchApplication").on("keyup", function() {

            filterApplications();

        });

        $("#statusFilter").on("change", function() {

            filterApplications();

        });

    });
</script>