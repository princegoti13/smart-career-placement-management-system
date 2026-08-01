<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* Check Company ID */

if (!isset($_GET['id']) || empty($_GET['id'])) {

    header("Location: manage_companies.php");
    exit;
}

$id = (int)$_GET['id'];

/* ===========================================
   Get Company Details
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT company_logo
     FROM companies
     WHERE id='$id'
     LIMIT 1"

);

if (mysqli_num_rows($query) == 0) {

    header("Location: manage_companies.php");
    exit;
}

$company = mysqli_fetch_assoc($query);

/* ===========================================
   Delete Company Logo
=========================================== */

if (
    !empty($company['company_logo']) &&
    $company['company_logo'] != "default-company.png"
) {

    $logo = "../assets/uploads/company/" . $company['company_logo'];

    if (file_exists($logo)) {

        unlink($logo);
    }
}

/* ===========================================
   Get All Jobs Of Company
=========================================== */

$jobs = mysqli_query(

    $conn,

    "SELECT id
     FROM jobs
     WHERE company_id='$id'"

);

while ($job = mysqli_fetch_assoc($jobs)) {

    mysqli_query(

        $conn,

        "DELETE FROM applications
         WHERE job_id='" . $job['id'] . "'"

    );
}

/* ===========================================
   Delete Company Jobs
=========================================== */

mysqli_query(

    $conn,

    "DELETE FROM jobs
     WHERE company_id='$id'"

);

/* ===========================================
   Delete Company
=========================================== */

if (

    mysqli_query(

        $conn,

        "DELETE FROM companies
         WHERE id='$id'"

    )

) {

    $_SESSION['success'] = "Company Deleted Successfully.";
} else {

    $_SESSION['error'] = "Unable To Delete Company.";
}

/* ===========================================
   Redirect
=========================================== */

header("Location: manage_companies.php");
exit;
