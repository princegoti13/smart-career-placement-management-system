<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

if (!isset($_GET['id'])) {

    header("Location: manage_jobs.php");
    exit;
}

$id = (int)$_GET['id'];

$company_id = $_SESSION['company_id'];

/* Delete Only Own Job */

mysqli_query(
    $conn,
    "DELETE FROM jobs
     WHERE id='$id'
     AND company_id='$company_id'"
);

header("Location: manage_jobs.php");

exit;
