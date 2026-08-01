<?php

require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

$company_id = $_SESSION['company_id'];

$page_title = "Manage Jobs";
$body_class = "company-theme";

$jobs = mysqli_query($conn, "

SELECT

    j.*,

    (
        SELECT COUNT(*)
        FROM applications a
        WHERE a.job_id=j.id

    ) AS total_applications

FROM jobs j

WHERE j.company_id='$company_id'

ORDER BY j.created_at DESC

");

include('../includes/header.php');
include('../includes/sidebar_company.php');


?>
<?php if (isset($_SESSION['success'])) { ?>

    <div class="alert alert-success alert-dismissible fade show">

        <?php
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

<?php } ?>

<link rel="stylesheet" href="../assets/css/company.css">

<div class="main-content">

    <?php include('../includes/topbar_company.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <p class="text-muted mb-0">

                        <strong>

                            <p class="text-muted">

                                View, Edit And Manage All Your Posted Jobs.

                            </p>

                        </strong>

                    </p>

                </div>

                <a
                    href="add_job.php"
                    class="btn btn-success">

                    <i class="fas fa-plus"></i>

                    Post New Job

                </a>

            </div>

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-10">

                            <input
                                type="text"
                                id="searchJob"
                                class="form-control"
                                placeholder="Search By Job Title...">

                        </div>

                        <div class="col-md-2">

                            <button
                                class="btn btn-success w-100">

                                <i class="fas fa-search"></i>

                                Search

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle" id="jobsTable">

                            <thead class="table-success">

                                <tr>

                                    <th>#</th>

                                    <th>Job Title</th>

                                    <th>Category</th>

                                    <th>Type</th>

                                    <th>Status</th>

                                    <th>Applications</th>

                                    <th>Posted On</th>

                                    <th class="text-center">

                                        Actions

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $i = 1;

                                if (mysqli_num_rows($jobs) > 0) {

                                    while ($job = mysqli_fetch_assoc($jobs)) {

                                ?>

                                        <tr>

                                            <!-- SR No -->

                                            <td>

                                                <?php echo $i++; ?>

                                            </td>

                                            <!-- Job Title -->

                                            <td>

                                                <strong>

                                                    <?php echo htmlspecialchars($job['job_title']); ?>

                                                </strong>

                                            </td>

                                            <!-- Category -->

                                            <td>

                                                <?php echo htmlspecialchars($job['category']); ?>

                                            </td>

                                            <!-- Job Type -->

                                            <td>

                                                <span class="badge bg-primary">

                                                    <?php echo htmlspecialchars($job['job_type']); ?>

                                                </span>

                                            </td>

                                            <!-- Status -->

                                            <td>

                                                <?php

                                                if ($job['status'] == "Active") {

                                                ?>

                                                    <span class="badge bg-success">

                                                        Active

                                                    </span>

                                                <?php

                                                } else {

                                                ?>

                                                    <span class="badge bg-danger">

                                                        Closed

                                                    </span>

                                                <?php } ?>

                                            </td>

                                            <!-- Applications -->

                                            <td>

                                                <span class="badge bg-info text-dark">

                                                    <?php echo $job['total_applications']; ?>

                                                </span>

                                            </td>

                                            <!-- Posted Date -->

                                            <td>

                                                <?php echo date("d M Y", strtotime($job['created_at'])); ?>

                                            </td>

                                            <!-- Actions -->

                                            <td class="text-center">

                                                <a href="view_applications.php"
                                                    class="btn btn-info btn-sm">

                                                    <i class="fas fa-users"></i>

                                                </a>

                                                <a href="edit_job.php?id=<?php echo $job['id']; ?>"
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

                                    <?php

                                    }
                                } else {

                                    ?>

                                    <tr>

                                        <td colspan="8" class="text-center py-5">

                                            <i class="fas fa-briefcase fa-3x text-secondary mb-3"></i>

                                            <h5>

                                                No Jobs Found

                                            </h5>

                                            <p class="text-muted">

                                                You Have Not Posted Any Jobs Yet.

                                            </p>

                                        </td>

                                    </tr>

                                <?php

                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php include('../includes/footer_company.php'); ?>
</div>



<script>
    $(document).ready(function() {

        // ==========================
        // Search Job
        // ==========================

        $("#searchJob").on("keyup", function() {

            var value = $(this).val().toLowerCase();

            $("#jobsTable tbody tr").filter(function() {

                $(this).toggle(

                    $(this).text().toLowerCase().indexOf(value) > -1

                );

            });

        });

        // ==========================
        // Delete Job
        // ==========================

        $(".deleteJobBtn").click(function() {

            var job_id = $(this).data("id");

            Swal.fire({

                title: "Delete Job?",

                text: "This Job Will Be Permanently Deleted.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#198754",

                cancelButtonColor: "#dc3545",

                confirmButtonText: "Yes, Delete",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href =
                        "delete_job.php?id=" + job_id;

                }

            });

        });

    });
</script>