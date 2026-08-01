<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   AJAX Request Check
=========================================== */

if ($_SERVER['REQUEST_METHOD'] != "POST") {

    exit;
}

if (
    !isset($_POST['id']) ||
    !isset($_POST['status'])
) {

    exit;
}

$id = (int)$_POST['id'];

$status = cleanInput($_POST['status']);

/* ===========================================
   Valid Status Check
=========================================== */

$validStatus = [

    "Pending",
    "Shortlisted",
    "Accepted",
    "Rejected"

];

if (!in_array($status, $validStatus)) {

    exit;
}

/* ===========================================
   Check Application
=========================================== */

$check = mysqli_query(

    $conn,

    "SELECT id
     FROM applications
     WHERE id='$id'
     LIMIT 1"

);

if (mysqli_num_rows($check) == 0) {

    exit;
}

/* ===========================================
   Update Status
=========================================== */

$stmt = mysqli_prepare(

    $conn,

    "UPDATE applications
     SET status=?
     WHERE id=?"

);

mysqli_stmt_bind_param(

    $stmt,

    "si",

    $status,
    $id

);

if (mysqli_stmt_execute($stmt)) {

    echo "success";
} else {

    echo "error";
}
