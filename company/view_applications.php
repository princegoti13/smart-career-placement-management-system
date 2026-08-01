<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

$company_id = $_SESSION['company_id'];

$applications = mysqli_query($conn, "

SELECT

a.*,

s.id AS student_id,
s.full_name,
s.email,
s.phone,
s.profile_photo,
s.resume,

s.gender,
s.dob,
s.address,
s.city,
s.state,
s.country,
s.pincode,

s.enrollment_no,
s.course,
s.semester,
s.college_name,
s.university,
s.passing_year,
s.cgpa,

s.linkedin,
s.github,
s.portfolio,

s.preferred_role,
s.preferred_location,
s.employment_type,
s.expected_salary,

j.job_title

FROM applications a

INNER JOIN students s
ON a.student_id = s.id

INNER JOIN jobs j
ON a.job_id = j.id

WHERE j.company_id = '$company_id'

ORDER BY a.application_date DESC

");

$page_title = "View Applications";
$body_class = "company-theme";

include('../includes/header.php');
include('../includes/sidebar_company.php');
?>

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

                                View All Applications Received For Your Jobs.

                            </p>

                        </strong>

                    </p>

                </div>

            </div>

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-10">

                            <input
                                type="text"
                                id="searchStudent"
                                class="form-control"
                                placeholder="Search Student Name Or Email...">

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

                        <table class="table table-hover align-middle" id="applicationsTable">

                            <thead class="table-success">

                                <tr>

                                    <th>Photo</th>

                                    <th>Student</th>

                                    <th>Job Title</th>

                                    <th>Resume</th>

                                    <th>Status</th>

                                    <th>Applied On</th>

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
                                                    height="60"
                                                    style="object-fit:cover;">

                                            </td>

                                            <!-- Student Information -->

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

                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-sm viewStudentBtn"
                                                    data-bs-target="#application<?php echo $app['id']; ?>">

                                                    <i class="fas fa-eye"></i>

                                                    <span class="btn-text">

                                                        View

                                                    </span>

                                                </button>

                                                <button
                                                    class="btn btn-primary btn-sm mb-1 statusBtn"
                                                    data-id="<?php echo $app['id']; ?>"
                                                    data-status="Shortlisted">

                                                    <i class="fas fa-list"></i>

                                                </button>

                                                <button
                                                    class="btn btn-success btn-sm mb-1 statusBtn"
                                                    data-id="<?php echo $app['id']; ?>"
                                                    data-status="Accepted">

                                                    <i class="fas fa-check"></i>

                                                </button>

                                                <button
                                                    class="btn btn-danger btn-sm mb-1 statusBtn"
                                                    data-id="<?php echo $app['id']; ?>"
                                                    data-status="Rejected">

                                                    <i class="fas fa-times"></i>

                                                </button>

                                            </td>

                                        </tr>

                                        <!-- Student Details -->

                                        <tr>

                                            <td colspan="7" class="border-0 p-0">

                                                <div
                                                    class="collapse"
                                                    id="application<?php echo $app['id']; ?>">

                                                    <div class="card border-0 shadow-sm mt-3">

                                                        <div class="card-body">

                                                            <div class="row">

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

                                                                <div class="col-lg-9">

                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>Phone</strong><br>

                                                                            <?php echo htmlspecialchars($app['phone']); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>Gender</strong><br>

                                                                            <?php echo htmlspecialchars($app['gender'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>Course</strong><br>

                                                                            <?php echo htmlspecialchars($app['course'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>Semester</strong><br>

                                                                            <?php echo htmlspecialchars($app['semester'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>College</strong><br>

                                                                            <?php echo htmlspecialchars($app['college_name'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>University</strong><br>

                                                                            <?php echo htmlspecialchars($app['university'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>CGPA</strong><br>

                                                                            <?php echo htmlspecialchars($app['cgpa'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                        <div class="col-md-6 mb-3">

                                                                            <strong>Preferred Role</strong><br>

                                                                            <?php echo htmlspecialchars($app['preferred_role'] ?? 'Not Updated'); ?>

                                                                        </div>

                                                                    </div>

                                                                    <hr>

                                                                    <strong>Resume :</strong>

                                                                    <?php if (!empty($app['resume'])) { ?>

                                                                        <a
                                                                            href="../assets/uploads/resumes/<?php echo $app['resume']; ?>"
                                                                            target="_blank"
                                                                            class="btn btn-success btn-sm ms-2">

                                                                            Download Resume

                                                                        </a>

                                                                    <?php } else { ?>

                                                                        <span class="text-danger">

                                                                            Not Uploaded

                                                                        </span>

                                                                    <?php } ?>

                                                                    <hr>

                                                                    <h5 class="fw-bold mb-3">

                                                                        <i class="fas fa-code text-primary"></i>

                                                                        Skills

                                                                    </h5>

                                                                    <?php

                                                                    $skills = mysqli_query(
                                                                        $conn,
                                                                        "SELECT skill_name
     FROM student_skills
     WHERE student_id='" . $app['student_id'] . "'
     ORDER BY skill_name ASC"
                                                                    );

                                                                    if (mysqli_num_rows($skills) > 0) {

                                                                        while ($skill = mysqli_fetch_assoc($skills)) {

                                                                    ?>

                                                                            <span class="badge bg-primary me-2 mb-2 p-2">

                                                                                <?php echo htmlspecialchars($skill['skill_name']); ?>

                                                                            </span>

                                                                        <?php

                                                                        }
                                                                    } else {

                                                                        ?>

                                                                        <p class="text-muted mb-0">

                                                                            No Skills Added.

                                                                        </p>

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

                                        <td colspan="7" class="text-center py-5">

                                            <i class="fas fa-user-graduate fa-4x text-secondary mb-3"></i>

                                            <h5>No Applications Found</h5>

                                            <p class="text-muted">

                                                No Students Have Applied For Your Jobs Yet.

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

    </div>
    <?php include('../includes/footer_company.php'); ?>
</div>



<script>
    $(document).ready(function() {

        /* ============================
           Search Student
        ============================ */

        $("#searchStudent").on("keyup", function() {

            var value = $(this).val().toLowerCase();

            $("#applicationsTable tbody tr").filter(function() {

                $(this).toggle(

                    $(this).text().toLowerCase().indexOf(value) > -1

                );

            });

        });

        /* ============================
           Update Status
        ============================ */

        $(".statusBtn").click(function() {

            var button = $(this);

            var application_id = button.data("id");

            var status = button.data("status");

            Swal.fire({

                title: "Update Application?",

                text: "Change Status To " + status + " ?",

                icon: "question",

                showCancelButton: true,

                confirmButtonColor: "#198754",

                cancelButtonColor: "#dc3545",

                confirmButtonText: "Yes",

                cancelButtonText: "Cancel"

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "ajax/update_application_status.php",

                        type: "POST",

                        data: {

                            application_id: application_id,

                            status: status

                        },

                        success: function(response) {

                            Swal.fire({

                                icon: "success",

                                title: "Success",

                                text: "Application Status Updated.",

                                timer: 1500,

                                showConfirmButton: false

                            }).then(() => {

                                location.reload();

                            });

                        },

                        error: function() {

                            Swal.fire(

                                "Error",

                                "Something Went Wrong.",

                                "error"

                            );

                        }

                    });

                }

            });

        });

    });

    $(document).on("click", ".viewStudentBtn", function() {

        var button = $(this);

        var target = $(button.data("bs-target"));

        target.collapse("toggle");

        target.on("shown.bs.collapse", function() {

            button.find(".btn-text").text("Hide");

        });

        target.on("hidden.bs.collapse", function() {

            button.find(".btn-text").text("View");

        });

    });
</script>