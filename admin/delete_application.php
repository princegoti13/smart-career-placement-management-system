<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   Check Application ID
=========================================== */

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: manage_applications.php");
    exit;
}

$id = (int)$_GET['id'];

/* ===========================================
   Check Application Exists
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT id
     FROM applications
     WHERE id='$id'
     LIMIT 1"

);

if (mysqli_num_rows($query) == 0) {

    $_SESSION['error'] = "Application Not Found.";

    header("Location: manage_applications.php");
    exit;
}

/* ===========================================
   Delete Application
=========================================== */

if (mysqli_query(

    $conn,

    "DELETE FROM applications
     WHERE id='$id'"

)) {

    $_SESSION['success'] = "Application Deleted Successfully.";
} else {

    $_SESSION['error'] = "Unable To Delete Application.";
}

/* ===========================================
   Redirect
=========================================== */

header("Location: manage_applications.php");
exit;
