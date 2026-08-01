<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* Check Student ID */

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: manage_students.php");
    exit;
}

$id = (int)$_GET['id'];

/* Get Student Details */

$query = mysqli_query(
    $conn,
    "SELECT profile_photo, resume
     FROM students
     WHERE id='$id'
     LIMIT 1"
);

if (mysqli_num_rows($query) == 0) {

    header("Location: manage_students.php");
    exit;
}

$student = mysqli_fetch_assoc($query);

/* Delete Profile Photo */

if (
    !empty($student['profile_photo']) &&
    $student['profile_photo'] != "default-user.png"
) {

    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];

    if (file_exists($photo)) {

        unlink($photo);
    }
}

/* Delete Resume */

if (!empty($student['resume'])) {

    $resume = "../assets/uploads/resumes/" . $student['resume'];

    if (file_exists($resume)) {

        unlink($resume);
    }
}

/* Delete Applications */

mysqli_query(
    $conn,
    "DELETE FROM applications
     WHERE student_id='$id'"
);

/* Delete Student */

if (mysqli_query($conn, "DELETE FROM students WHERE id='$id'")) {

    $_SESSION['success'] = "Student Deleted Successfully.";
} else {

    $_SESSION['error'] = "Unable To Delete Student.";
}

header("Location: manage_students.php");
exit;
