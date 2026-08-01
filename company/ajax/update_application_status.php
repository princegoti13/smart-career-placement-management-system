<?php
require_once('../../includes/session.php');
/** @var mysqli $conn */

checkCompany();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $application_id = (int)$_POST['application_id'];

    $status = cleanInput($_POST['status']);

    $allowed = [

        "Pending",
        "Shortlisted",
        "Accepted",
        "Rejected"

    ];

    if (!in_array($status, $allowed)) {

        exit("Invalid Status");
    }

    mysqli_query($conn, "

    UPDATE applications

    SET status='$status'

    WHERE id='$application_id'

    ");

    echo "success";
}
