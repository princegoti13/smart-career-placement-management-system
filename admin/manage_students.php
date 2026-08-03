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
        "SELECT * FROM admins WHERE id='$admin_id' LIMIT 1"
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

            full_name LIKE '%$search%'

            OR email LIKE '%$search%'

            OR phone LIKE '%$search%'

            OR college_name LIKE '%$search%'

            OR course LIKE '%$search%'";
    }
}

/* ===========================
   Total Students
=========================== */

$totalQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM students
     $where"

);

$totalStudents = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalStudents / $limit);

/* ===========================
   Student List
=========================== */

$students = mysqli_query(

    $conn,

    "SELECT *

     FROM students

     $where

     ORDER BY id DESC

     LIMIT $offset,$limit"

);

$page_title = "Manage Students";
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

                            View, Search, Edit And Manage All Registered Students.

                        </p>

                    </strong>

                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Students :
                    <?php echo $totalStudents; ?>

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
                                id="studentSearch"
                                class="form-control"
                                placeholder="Search By Name, Email, Phone, Course Or College..."
                                value="<?php echo htmlspecialchars($search); ?>">

                        </div>

                        <!-- <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100">

                                <i class="fas fa-search me-2"></i>

                                Search

                            </button>

                        </div> -->

                    </div>

                </form>

            </div>

        </div>

        <!-- ===========================
     Student Table
=========================== -->

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                        id="studentsTable">

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

                                <th>

                                    College

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

                            if (mysqli_num_rows($students) > 0) {

                                while ($student = mysqli_fetch_assoc($students)) {

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

                                        <!-- Photo -->

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

                                            <h6 class="mb-1 fw-bold">

                                                <?php echo htmlspecialchars($student['full_name']); ?>

                                            </h6>

                                            <small class="text-muted d-block">

                                                <i class="fas fa-envelope me-1"></i>

                                                <?php echo htmlspecialchars($student['email']); ?>

                                            </small>

                                            <small class="text-muted">

                                                <i class="fas fa-phone me-1"></i>

                                                <?php echo htmlspecialchars($student['phone']); ?>

                                            </small>

                                        </td>

                                        <!-- Course -->

                                        <td>

                                            <?php echo htmlspecialchars($student['course']); ?>

                                            <br>

                                            <small class="text-muted">

                                                Semester :
                                                <?php echo htmlspecialchars($student['semester']); ?>

                                            </small>

                                        </td>

                                        <!-- College -->

                                        <td>

                                            <?php echo htmlspecialchars($student['college_name']); ?>

                                        </td>

                                        <!-- Status -->

                                        <td>

                                            <?php

                                            if (!empty($student['resume'])) {

                                                echo '<span class="badge bg-success">Profile Complete</span>';
                                            } else {

                                                echo '<span class="badge bg-warning text-dark">Incomplete</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- Actions -->

                                        <td class="text-center">

                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm mb-1 viewStudentBtn"
                                                data-bs-target="#student<?php echo $student['id']; ?>">

                                                <i class="fas fa-eye"></i>

                                                <span class="btn-text">

                                                    View

                                                </span>

                                            </button>

                                            <a
                                                href="edit_student.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-warning btn-sm mb-1">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <button
                                                class="btn btn-danger btn-sm mb-1 deleteStudentBtn"
                                                data-id="<?php echo $student['id']; ?>">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td colspan="6" class="border-0 p-0">

                                            <div
                                                class="collapse mt-2"
                                                id="student<?php echo $student['id']; ?>">

                                                <div class="card border-0 shadow-sm">

                                                    <div class="card-body">

                                                        <div class="row">

                                                            <!-- Left -->

                                                            <div class="col-lg-3 text-center">

                                                                <img
                                                                    src="<?php echo $photo; ?>"
                                                                    class="profile-image mb-3"
                                                                    alt="Student">

                                                                <h5>

                                                                    <?php echo htmlspecialchars($student['full_name']); ?>

                                                                </h5>

                                                                <p class="text-muted">

                                                                    <?php echo htmlspecialchars($student['email']); ?>

                                                                </p>

                                                            </div>

                                                            <!-- Right -->

                                                            <div class="col-lg-9">

                                                                <div class="row">

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Phone</strong><br>

                                                                        <?php echo htmlspecialchars($student['phone']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Gender</strong><br>

                                                                        <?php echo htmlspecialchars($student['gender'] ?: "Not Updated"); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Date Of Birth</strong><br>

                                                                        <?php echo !empty($student['dob']) ? date("d M Y", strtotime($student['dob'])) : "Not Updated"; ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Course</strong><br>

                                                                        <?php echo htmlspecialchars($student['course']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Semester</strong><br>

                                                                        <?php echo htmlspecialchars($student['semester']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>College</strong><br>

                                                                        <?php echo htmlspecialchars($student['college_name']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>University</strong><br>

                                                                        <?php echo htmlspecialchars($student['university']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>CGPA</strong><br>

                                                                        <?php echo htmlspecialchars($student['cgpa']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Preferred Role</strong><br>

                                                                        <?php echo htmlspecialchars($student['preferred_role']); ?>

                                                                    </div>

                                                                    <div class="col-md-6 mb-3">

                                                                        <strong>Skills</strong><br>

                                                                        <?php echo !empty($student['skills']) ? htmlspecialchars($student['skills']) : "Not Updated"; ?>

                                                                    </div>

                                                                    <div class="col-md-12 mb-3">

                                                                        <strong>Address</strong><br>

                                                                        <?php echo !empty($student['address']) ? htmlspecialchars($student['address']) : "Not Updated"; ?>

                                                                    </div>

                                                                </div>

                                                                <hr>

                                                                <div class="d-flex justify-content-between align-items-center">

                                                                    <div>

                                                                        <?php if (!empty($student['resume'])) { ?>

                                                                            <a
                                                                                href="../assets/uploads/resumes/<?php echo $student['resume']; ?>"
                                                                                target="_blank"
                                                                                class="btn btn-success">

                                                                                <i class="fas fa-download me-2"></i>

                                                                                Resume

                                                                            </a>

                                                                        <?php } ?>

                                                                    </div>

                                                                    <!-- <div>

                                                                        <a
                                                                            href="edit_student.php?id=<?php echo $student['id']; ?>"
                                                                            class="btn btn-warning">

                                                                            <i class="fas fa-edit me-2"></i>

                                                                            Edit Student

                                                                        </a>

                                                                    </div> -->

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

                                    <td colspan="6" class="text-center py-5">

                                        <i class="fas fa-user-graduate fa-4x text-secondary mb-3"></i>

                                        <h5>

                                            No Students Found

                                        </h5>

                                        <p class="text-muted">

                                            No Student Records Available.

                                        </p>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- ===========================
     Pagination
=========================== -->

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
    /* ===========================
   View / Hide Student Details
=========================== */

    $(document).on("click", ".viewStudentBtn", function() {

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

    $("#studentSearch").on("keyup", function() {

        var value = $(this).val().toLowerCase();

        $("#studentsTable tbody tr").filter(function() {

            $(this).toggle(

                $(this).text().toLowerCase().indexOf(value) > -1

            );

        });

    });

    /* ===========================
       Delete Student
    =========================== */

    $(document).on("click", ".deleteStudentBtn", function() {

        var id = $(this).data("id");

        Swal.fire({

            title: "Delete Student?",

            text: "This Student Will Be Permanently Deleted.",

            icon: "warning",

            showCancelButton: true,

            confirmButtonColor: "#dc3545",

            cancelButtonColor: "#6c757d",

            confirmButtonText: "Yes, Delete",

            cancelButtonText: "Cancel"

        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = "delete_student.php?id=" + id;

            }

        });

    });

    setTimeout(function() {

        $(".alert").fadeOut(500);

    }, 3000);
</script>