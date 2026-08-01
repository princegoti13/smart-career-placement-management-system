<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkStudent();

$id = $_SESSION['student_id'];

$query = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$student = mysqli_fetch_assoc($query);
$skills = mysqli_query(
    $conn,
    "SELECT skill_name
     FROM student_skills
     WHERE student_id = '$id'
     ORDER BY skill_name ASC"
);
// $photo = !empty($student['profile_photo'])
//     ? "../assets/uploads/profiles/" . $student['profile_photo']
//     : "../assets/images/default-user.png";

if (
    !empty($student['profile_photo']) &&
    $student['profile_photo'] != "default-user.png"
) {

    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
} else {

    $photo = "../assets/images/default-user.png";
}

$page_title = "My Profile";
$body_class = "student-theme";

include('../includes/header.php');
include('../includes/sidebar_student.php');
?>

<div class="main-content">

    <?php include('../includes/topbar.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-4">

                    <div class="card">

                        <div class="card-body text-center">

                            <img
                                src=" <?php echo $photo; ?>"
                                class="profile-image mb-3"
                                alt="Profile">

                            <h3><?php echo htmlspecialchars($student['full_name']); ?></h3>

                            <p class="text-muted">

                                <?php echo htmlspecialchars($student['email']); ?>

                            </p>

                            <hr>

                            <div class="row">

                                <div class="col-6">

                                    <!-- <h6>Course</h6>
                                    <p>BCA</p> -->
                                    <h6>Course</h6>

                                    <p><?php echo htmlspecialchars($student['course'] ?? 'Not Updated'); ?></p>

                                </div>

                                <div class="col-6">

                                    <!-- <h6>Semester</h6>
                                    <p>5</p> -->
                                    <h6>Semester</h6>

                                    <p><?php echo htmlspecialchars($student['semester'] ?? 'Not Updated'); ?></p>

                                </div>

                            </div>

                            <a href="edit_profile.php"
                                class="btn btn-primary w-100 mt-3">

                                <i class="fas fa-edit"></i>

                                Edit Profile

                            </a>

                        </div>

                    </div>

                </div>

                <div class="col-lg-8">

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Personal Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>Full Name</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['full_name']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Email</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['email']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Mobile Number</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['phone']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Gender</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['gender'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Date Of Birth</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['dob'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Address</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['address'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Academic Information -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Academic Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>Enrollment Number</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['enrollment_no'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>College Name</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['college_name'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>University</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['university'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Passing Year</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['passing_year'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>CGPA</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['cgpa'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Skills -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Skills

                            </h5>

                        </div>

                        <div class="card-body">

                            <?php if (mysqli_num_rows($skills) > 0) { ?>

                                <?php while ($skill = mysqli_fetch_assoc($skills)) { ?>

                                    <span class="badge bg-primary me-2 mb-2 p-2">
                                        <?php echo htmlspecialchars($skill['skill_name']); ?>
                                    </span>

                                <?php } ?>

                            <?php } else { ?>

                                <p class="text-muted mb-0">
                                    No Skills Added Yet.
                                </p>

                            <?php } ?>

                        </div>

                    </div>

                    <!-- Resume -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Resume

                            </h5>

                        </div>

                        <div class="card-body">

                            <?php if (!empty($student['resume'])) { ?>

                                <a
                                    href="../assets/uploads/resumes/<?php echo htmlspecialchars($student['resume']); ?>"
                                    target="_blank"
                                    class="btn btn-success">

                                    <i class="fas fa-download"></i>

                                    Download Resume

                                </a>

                            <?php } else { ?>

                                <p class="text-danger">

                                    Resume Not Uploaded

                                </p>

                            <?php } ?>

                        </div>

                    </div>

                    <!-- Social Links -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Social Links

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>LinkedIn</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['linkedin'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>GitHub</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['github'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-12 mb-3">

                                    <label>Portfolio Website</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['portfolio'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Career Preferences -->

                    <div class="card">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Career Preferences

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label>Preferred Role</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['preferred_role'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Preferred Location</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['preferred_location'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Expected Salary</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($student['expected_salary'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include('../includes/footer_student.php'); ?>