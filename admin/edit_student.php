<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

if (!isset($_GET['id'])) {

    header("Location: manage_students.php");
    exit;
}

$id = (int)$_GET['id'];

/* ===========================
   Fetch Student
=========================== */

$query = mysqli_query(

    $conn,

    "SELECT *
     FROM students
     WHERE id='$id'
     LIMIT 1"

);

if (mysqli_num_rows($query) == 0) {

    header("Location: manage_students.php");
    exit;
}

$student = mysqli_fetch_assoc($query);

$error = "";
$success = "";

/* ===========================
   Update Student
=========================== */

if (isset($_POST['update_student'])) {

    $full_name       = cleanInput($_POST['full_name']);
    $phone           = cleanInput($_POST['phone']);
    $gender          = cleanInput($_POST['gender']);
    $dob             = $_POST['dob'];

    $course          = cleanInput($_POST['course']);
    $semester        = cleanInput($_POST['semester']);
    $college_name    = cleanInput($_POST['college_name']);
    $university      = cleanInput($_POST['university']);

    $cgpa            = cleanInput($_POST['cgpa']);
    $preferred_role  = cleanInput($_POST['preferred_role']);
    $address         = cleanInput($_POST['address']);

    /* Skills */

    if (isset($_POST['skills'])) {

        $skills = implode(", ", $_POST['skills']);
    } else {

        $skills = "";
    }

    /* ===========================
       Profile Photo Upload
    =========================== */

    $profile_photo = $student['profile_photo'];

    if (!empty($_FILES['profile_photo']['name'])) {

        $imageName = time() . "_" . basename($_FILES['profile_photo']['name']);

        $target = "../assets/uploads/profiles/" . $imageName;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {

            if (
                !empty($student['profile_photo']) &&
                $student['profile_photo'] != "default-user.png"
            ) {

                $oldPhoto = "../assets/uploads/profiles/" . $student['profile_photo'];

                if (file_exists($oldPhoto)) {

                    unlink($oldPhoto);
                }
            }

            $profile_photo = $imageName;
        }
    }

    /* ===========================
       Resume Upload
    =========================== */

    $resume = $student['resume'];

    if (!empty($_FILES['resume']['name'])) {

        $resumeName = time() . "_" . basename($_FILES['resume']['name']);

        $targetResume = "../assets/uploads/resumes/" . $resumeName;

        if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetResume)) {

            if (!empty($student['resume'])) {

                $oldResume = "../assets/uploads/resumes/" . $student['resume'];

                if (file_exists($oldResume)) {

                    unlink($oldResume);
                }
            }

            $resume = $resumeName;
        }
    }

    /* ===========================
       Update Query
    =========================== */

    $sql = "UPDATE students SET

            full_name=?,
            phone=?,
            gender=?,
            dob=?,
            course=?,
            semester=?,
            college_name=?,
            university=?,
            cgpa=?,
            preferred_role=?,
            skills=?,
            address=?,
            profile_photo=?,
            resume=?

            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ssssssssssssssi",

        $full_name,
        $phone,
        $gender,
        $dob,
        $course,
        $semester,
        $college_name,
        $university,
        $cgpa,
        $preferred_role,
        $skills,
        $address,
        $profile_photo,
        $resume,
        $id

    );

    if (mysqli_stmt_execute($stmt)) {

        $success = "Student Updated Successfully. Redirecting To Manage Students...";

        $query = mysqli_query(

            $conn,

            "SELECT *
             FROM students
             WHERE id='$id'
             LIMIT 1"

        );

        $student = mysqli_fetch_assoc($query);

        header("Refresh:3;url=manage_students.php");
    } else {

        $error = "Something Went Wrong.";
    }
}

$page_title = "Edit Student";
$body_class = "admin-theme";

include('../includes/header.php');
include('../includes/sidebar_admin.php');
?>

<link rel="stylesheet" href="../assets/css/admin.css">

<div class="main-content">

    <?php include('../includes/topbar_admin.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-body">

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

                    <form
                        method="POST"
                        enctype="multipart/form-data">

                        <div class="row">

                            <!-- ===========================================
                     Student Profile
                =========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-user-graduate me-2"></i>

                                            Student Profile

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col-md-3 text-center">

                                                <?php

                                                if (
                                                    !empty($student['profile_photo']) &&
                                                    $student['profile_photo'] != "default-user.png"
                                                ) {

                                                    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
                                                } else {

                                                    $photo = "../assets/images/default-user.png";
                                                }

                                                ?>

                                                <img
                                                    src="<?php echo $photo; ?>"
                                                    class="profile-image mb-3"
                                                    alt="Student">

                                                <input
                                                    type="file"
                                                    name="profile_photo"
                                                    class="form-control py-2">

                                            </div>

                                            <div class="col-md-9">

                                                <div class="row">

                                                    <!-- Full Name -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Full Name

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="full_name"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($student['full_name']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Email -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Email Address

                                                        </label>

                                                        <input
                                                            type="email"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($student['email']); ?>"
                                                            readonly>

                                                    </div>

                                                    <!-- Phone -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Phone Number

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="phone"
                                                            class="form-control"
                                                            maxlength="10"
                                                            value="<?php echo htmlspecialchars($student['phone']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Gender -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Gender

                                                        </label>

                                                        <select
                                                            name="gender"
                                                            class="form-select"
                                                            required>

                                                            <option value="Male" <?php if ($student['gender'] == "Male") echo "selected"; ?>>

                                                                Male

                                                            </option>

                                                            <option value="Female" <?php if ($student['gender'] == "Female") echo "selected"; ?>>

                                                                Female

                                                            </option>

                                                            <option value="Other" <?php if ($student['gender'] == "Other") echo "selected"; ?>>

                                                                Other

                                                            </option>

                                                        </select>

                                                    </div>

                                                    <!-- Date Of Birth -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Date Of Birth

                                                        </label>

                                                        <input
                                                            type="date"
                                                            name="dob"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($student['dob']); ?>">

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
                     Academic Information
                =========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-graduation-cap me-2"></i>

                                            Academic Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Course -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Course

                                                </label>

                                                <select
                                                    name="course"
                                                    class="form-select"
                                                    required>

                                                    <?php

                                                    $courses = [

                                                        "BCA",
                                                        "B.Sc IT",
                                                        "B.Tech",
                                                        "B.Com",
                                                        "BBA",
                                                        "MCA",
                                                        "M.Sc IT",
                                                        "M.Tech",
                                                        "MBA"

                                                    ];

                                                    foreach ($courses as $course) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $course; ?>"
                                                            <?php if ($student['course'] == $course) echo "selected"; ?>>

                                                            <?php echo $course; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Semester -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Semester

                                                </label>

                                                <select
                                                    name="semester"
                                                    class="form-select"
                                                    required>

                                                    <?php

                                                    for ($i = 1; $i <= 8; $i++) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $i; ?>"
                                                            <?php if ($student['semester'] == $i) echo "selected"; ?>>

                                                            Semester <?php echo $i; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- College -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    College Name

                                                </label>

                                                <input
                                                    type="text"
                                                    name="college_name"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['college_name']); ?>"
                                                    required>

                                            </div>

                                            <!-- University -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    University

                                                </label>

                                                <input
                                                    type="text"
                                                    name="university"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['university']); ?>"
                                                    required>

                                            </div>

                                            <!-- CGPA -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    CGPA

                                                </label>

                                                <input
                                                    type="text"
                                                    name="cgpa"
                                                    class="form-control"
                                                    placeholder="Example : 8.50"
                                                    value="<?php echo htmlspecialchars($student['cgpa']); ?>">

                                            </div>

                                            <!-- Preferred Role -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Preferred Role

                                                </label>

                                                <input
                                                    type="text"
                                                    name="preferred_role"
                                                    class="form-control"
                                                    placeholder="Example : PHP Developer"
                                                    value="<?php echo htmlspecialchars($student['preferred_role']); ?>">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
                     Skills & Resume
                =========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-code me-2"></i>

                                            Resume

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <!-- Skills -->

                                        

                                        <!-- Resume -->

                                        <div class="mb-4">

                                            <label>

                                                Upload Resume

                                            </label>

                                            <input
                                                type="file"
                                                name="resume"
                                                class="form-control py-2">

                                            <?php if (!empty($student['resume'])) { ?>

                                                <div class="mt-3">

                                                    <a
                                                        href="../assets/uploads/resumes/<?php echo $student['resume']; ?>"
                                                        target="_blank"
                                                        class="btn btn-success btn-sm">

                                                        <i class="fas fa-download me-2"></i>

                                                        View Current Resume

                                                    </a>

                                                </div>

                                            <?php } ?>

                                        </div>

                                        <!-- Address -->

                                        <div class="mb-3">

                                            <label>

                                                Address

                                            </label>

                                            <textarea
                                                name="address"
                                                rows="4"
                                                class="form-control"><?php echo htmlspecialchars($student['address']); ?></textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
                     Buttons
                =========================================== -->

                            <div class="col-12">

                                <div class="text-end">

                                    <a
                                        href="manage_students.php"
                                        class="btn btn-secondary">

                                        <i class="fas fa-arrow-left me-2"></i>

                                        Back

                                    </a>

                                    <button
                                        type="reset"
                                        class="btn btn-warning">

                                        <i class="fas fa-undo me-2"></i>

                                        Reset

                                    </button>

                                    <button
                                        type="submit"
                                        name="update_student"
                                        class="btn btn-primary">

                                        <i class="fas fa-save me-2"></i>

                                        Update Student

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include('../includes/footer.php'); ?>