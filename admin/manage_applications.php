<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   Admin Details
=========================================== */
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

/* ===========================================
   Pagination
=========================================== */

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page <= 0) {

    $page = 1;
}

$offset = ($page - 1) * $limit;

/* ===========================================
   Search
=========================================== */

$search = "";
$status = "";

$where = "WHERE 1=1";

if (!empty($_GET['search'])) {

    $search = cleanInput($_GET['search']);

    $where .= " AND (

        s.full_name LIKE '%$search%'

        OR c.company_name LIKE '%$search%'

        OR j.job_title LIKE '%$search%'

    )";
}

if (!empty($_GET['status'])) {

    $status = cleanInput($_GET['status']);

    $where .= " AND a.status='$status'";
}

/* ===========================================
   Total Applications
=========================================== */

$totalQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM applications a

     INNER JOIN students s
     ON a.student_id=s.id

     INNER JOIN jobs j
     ON a.job_id=j.id

     INNER JOIN companies c
     ON j.company_id=c.id

     $where"

);

$totalApplications = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalApplications / $limit);

/* ===========================================
   Applications List
=========================================== */

$applications = mysqli_query(

    $conn,

    "SELECT

        a.*,

        s.id AS student_id,
        s.full_name,
        s.email,
        s.phone,
        s.gender,
        s.course,
        s.semester,
        s.college_name,
        s.university,
        s.cgpa,
        s.preferred_role,
        s.skills,
        s.address,
        s.profile_photo,
        s.resume,

        j.job_title,

        c.company_name

     FROM applications a

     INNER JOIN students s
     ON a.student_id=s.id

     INNER JOIN jobs j
     ON a.job_id=j.id

     INNER JOIN companies c
     ON j.company_id=c.id

     $where

     ORDER BY a.application_date DESC

     LIMIT $offset,$limit"

);

$page_title = "Manage Applications";
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

                            View, Search And Manage All Student Applications.

                        </p>

                    </strong>

                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Applications :
                    <?php echo $totalApplications; ?>

                </span>

            </div>

        </div>

        <!-- ===========================================
     Search & Filter
=========================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <!-- Live Search -->

                    <div class="col-md-8">

                        <input
                            type="text"
                            id="applicationSearch"
                            class="form-control"
                            placeholder="Search By Student, Company Or Job Title...">

                    </div>

                    <!-- Status Filter -->

                    <div class="col-md-4">

                        <select
                            class="form-select"
                            id="statusFilter">

                            <option value="">

                                All Status

                            </option>

                            <option value="Pending">

                                Pending

                            </option>

                            <option value="Shortlisted">

                                Shortlisted

                            </option>

                            <option value="Accepted">

                                Accepted

                            </option>

                            <option value="Rejected">

                                Rejected

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================================
     Applications Table
=========================================== -->

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="applicationsTable">

                        <thead class="table-primary">

                            <tr>

                                <th width="80">

                                    Photo

                                </th>

                                <th>

                                    Student

                                </th>

                                <th>

                                    Company

                                </th>

                                <th>

                                    Job Title

                                </th>

                                <th>

                                    Resume

                                </th>

                                <th>

                                    Status

                                </th>

                                <th>

                                    Applied On

                                </th>

                                <th class="text-center">

                                    Actions

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($applications) > 0) {

                                while ($app = mysqli_fetch_assoc($applications)) {

                                    if (
                                        !empty($app['profile_photo']) &&
                                        $app['profile_photo'] != "default-user.png"
                                    ) {

                                        $photo = "../assets/uploads/profiles/" . $app['profile_photo'];
                                    } else {

                                        $photo = "../assets/images/default-user.png";
                                    }

                            ?>

                                    <tr>

                                        <!-- Student Photo -->

                                        <td>

                                            <img
                                                src="<?php echo $photo; ?>"
                                                class="rounded-circle border"
                                                width="60"
                                                height="55"
                                                style="object-fit:cover;">

                                        </td>

                                        <!-- Student -->

                                        <td>

                                            <h6 class="mb-1">

                                                <?php echo htmlspecialchars($app['full_name']); ?>

                                            </h6>

                                            <small class="text-muted d-block">

                                                <i class="fas fa-envelope me-1"></i>

                                                <?php echo htmlspecialchars($app['email']); ?>

                                            </small>

                                            <small class="text-muted">

                                                <i class="fas fa-phone me-1"></i>

                                                <?php echo htmlspecialchars($app['phone']); ?>

                                            </small>

                                        </td>

                                        <!-- Company -->

                                        <td>

                                            <?php echo htmlspecialchars($app['company_name']); ?>

                                        </td>

                                        <!-- Job -->

                                        <td>

                                            <?php echo htmlspecialchars($app['job_title']); ?>

                                        </td>

                                        <!-- Resume -->

                                        <td>

                                            <?php if (!empty($app['resume'])) { ?>

                                                <a
                                                    href="../assets/uploads/resumes/<?php echo htmlspecialchars($app['resume']); ?>"
                                                    target="_blank"
                                                    class="btn btn-success btn-sm">

                                                    <i class="fas fa-download"></i>

                                                    Resume

                                                </a>

                                            <?php } else { ?>

                                                <span class="badge bg-secondary">

                                                    Not Uploaded

                                                </span>

                                            <?php } ?>

                                        </td>

                                        <!-- Status -->

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

                                                default:

                                                    echo '<span class="badge bg-secondary">'
                                                        . htmlspecialchars($app['status']) .
                                                        '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- Applied Date -->

                                        <td>

                                            <?php echo date("d M Y", strtotime($app['application_date'])); ?>

                                        </td>

                                        <!-- Actions -->

                                        <td class="text-center">

                                            <!-- View -->

                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm mb-1 viewApplicationBtn"
                                                data-bs-target="#application<?php echo $app['id']; ?>">

                                                <i class="fas fa-eye"></i>

                                                <span class="btn-text">

                                                    View

                                                </span>

                                            </button>

                                            <!-- Shortlist -->

                                            <button
                                                class="btn btn-primary btn-sm mb-1 statusBtn"
                                                data-id="<?php echo $app['id']; ?>"
                                                data-status="Shortlisted">

                                                <i class="fas fa-list"></i>

                                            </button>

                                            <!-- Accept -->

                                            <button
                                                class="btn btn-success btn-sm mb-1 statusBtn"
                                                data-id="<?php echo $app['id']; ?>"
                                                data-status="Accepted">

                                                <i class="fas fa-check"></i>

                                            </button>

                                            <!-- Reject -->

                                            <button
                                                class="btn btn-danger btn-sm mb-1 statusBtn"
                                                data-id="<?php echo $app['id']; ?>"
                                                data-status="Rejected">

                                                <i class="fas fa-times"></i>

                                            </button>

                                            <!-- Delete -->

                                            <button
                                                class="btn btn-dark btn-sm mb-1 deleteApplicationBtn"
                                                data-id="<?php echo $app['id']; ?>">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td colspan="8" class="border-0 p-0">

                                            <div
                                                class="collapse mt-2"
                                                id="application<?php echo $app['id']; ?>">

                                                <div class="card border-0 shadow-sm">

                                                    <div class="card-body">

                                                        <div class="row">

                                                            <!-- Student Profile -->

                                                            <div class="col-lg-3 text-center">

                                                                <img
                                                                    src="<?php echo $photo; ?>"
                                                                    class="profile-image mb-3"
                                                                    alt="Student">

                                                                <h5>

                                                                    <?php echo htmlspecialchars($app['full_name']); ?>

                                                                </h5>

                                                                <p class="text-muted">

                                                                    <?php echo htmlspecialchars($app['email']); ?>

                                                                </p>

                                                            </div>

                                                            <!-- Student Details -->

                                                            <div class="col-lg-9">

                                                                <div class="row">

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Phone</strong><br>

                                                                        <?php echo htmlspecialchars($app['phone']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Gender</strong><br>

                                                                        <?php echo htmlspecialchars($app['gender'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Course</strong><br>

                                                                        <?php echo htmlspecialchars($app['course'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Semester</strong><br>

                                                                        <?php echo htmlspecialchars($app['semester'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>College</strong><br>

                                                                        <?php echo htmlspecialchars($app['college_name'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>University</strong><br>

                                                                        <?php echo htmlspecialchars($app['university'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>CGPA</strong><br>

                                                                        <?php echo htmlspecialchars($app['cgpa'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Preferred Role</strong><br>

                                                                        <?php echo htmlspecialchars($app['preferred_role'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Company</strong><br>

                                                                        <?php echo htmlspecialchars($app['company_name']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Job Title</strong><br>

                                                                        <?php echo htmlspecialchars($app['job_title']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Application Status</strong><br>

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

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Applied On</strong><br>

                                                                        <?php echo date("d M Y", strtotime($app['application_date'])); ?>

                                                                    </div>

                                                                </div>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Skills

                                                                </h6>

                                                                <?php

                                                                if (!empty($app['skills'])) {

                                                                    $skills = explode(",", $app['skills']);

                                                                    foreach ($skills as $skill) {

                                                                ?>

                                                                        <span class="badge bg-primary me-2 mb-2 p-2">

                                                                            <?php echo trim($skill); ?>

                                                                        </span>

                                                                <?php

                                                                    }
                                                                } else {

                                                                    echo "<span class='text-muted'>Not Updated</span>";
                                                                }

                                                                ?>

                                                                <hr>

                                                                <h6 class="fw-bold">

                                                                    Address

                                                                </h6>

                                                                <p class="text-muted">

                                                                    <?php echo !empty($app['address']) ? nl2br(htmlspecialchars($app['address'])) : "Not Updated"; ?>

                                                                </p>

                                                                <hr>

                                                                <?php if (!empty($app['resume'])) { ?>

                                                                    <a
                                                                        href="../assets/uploads/resumes/<?php echo htmlspecialchars($app['resume']); ?>"
                                                                        target="_blank"
                                                                        class="btn btn-success">

                                                                        <i class="fas fa-download me-2"></i>

                                                                        Download Resume

                                                                    </a>

                                                                <?php } ?>

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

                                    <td colspan="8" class="text-center py-5">

                                        <i class="fas fa-file-alt fa-4x text-secondary mb-3"></i>

                                        <h5>

                                            No Applications Found

                                        </h5>

                                        <p class="text-muted">

                                            No Student Applications Available.

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
                                href="?page=<?php echo $page - 1; ?>">

                                Previous

                            </a>

                        </li>

                    <?php } ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">

                            <a
                                class="page-link"
                                href="?page=<?php echo $i; ?>">

                                <?php echo $i; ?>

                            </a>

                        </li>

                    <?php } ?>

                    <?php if ($page < $totalPages) { ?>

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="?page=<?php echo $page + 1; ?>">

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

        /* ===========================================
           Live Search
        =========================================== */

        $("#applicationSearch").on("keyup", function() {

            var value = $(this).val().toLowerCase();

            $("#applicationsTable tbody tr").filter(function() {

                $(this).toggle(

                    $(this).text().toLowerCase().indexOf(value) > -1

                );

            });

        });

        /* ===========================================
           Status Filter
        =========================================== */

        $("#statusFilter").on("change", function() {

            var value = $(this).val().toLowerCase();

            $("#applicationsTable tbody tr").filter(function() {

                if (value == "") {

                    $(this).show();

                } else {

                    $(this).toggle(

                        $(this).text().toLowerCase().indexOf(value) > -1

                    );

                }

            });

        });

        /* ===========================================
           View / Hide Details
        =========================================== */

        $(".viewApplicationBtn").click(function() {

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

        /* ===========================================
           Status Update
        =========================================== */

        $(".statusBtn").click(function() {

            var id = $(this).data("id");

            var status = $(this).data("status");

            Swal.fire({

                title: "Update Application?",

                text: "Change Status To " + status + " ?",

                icon: "question",

                showCancelButton: true,

                confirmButtonColor: "#6f42c1",

                cancelButtonColor: "#dc3545",

                confirmButtonText: "Yes",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "update_application_status.php",

                        type: "POST",

                        data: {
                            id: id,
                            status: status
                        },

                        success: function(response) {

                            if (response.trim() == "success") {

                                Swal.fire({

                                    icon: "success",

                                    title: "Updated!",

                                    text: "Application Status Updated Successfully.",

                                    timer: 1500,

                                    showConfirmButton: false

                                }).then(function() {

                                    location.reload();

                                });

                            } else {

                                Swal.fire({

                                    icon: "error",

                                    title: "Error",

                                    text: "Unable To Update Application."

                                });

                            }

                        }

                    });

                }

            });

        });

        /* ===========================================
           Delete Application
        =========================================== */

        $(".deleteApplicationBtn").click(function() {

            var id = $(this).data("id");

            Swal.fire({

                title: "Delete Application?",

                text: "This Application Will Be Permanently Deleted.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#dc3545",

                cancelButtonColor: "#6c757d",

                confirmButtonText: "Yes, Delete",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = "delete_application.php?id=" + id;

                }

            });

        });

        /* ===========================================
           Auto Hide Alert
        =========================================== */

        setTimeout(function() {

            $(".alert").fadeOut();

        }, 3000);

    });
</script>