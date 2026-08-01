<?php
require_once('../../includes/session.php');
/** @var mysqli $conn */
checkStudent();

header('Content-Type: application/json');

$student_id = $_SESSION['student_id'];

if (isset($_POST['job_id'])) {

    $job_id = intval($_POST['job_id']);

    // Check Duplicate Application
    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM applications
         WHERE job_id=? AND student_id=?"
    );

    mysqli_stmt_bind_param($check, "ii", $job_id, $student_id);

    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {

        echo json_encode([
            "status" => "error",
            "message" => "You Have Already Applied For This Job."
        ]);

        exit;
    }

    // Apply Job
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO applications
        (job_id,student_id,status)
        VALUES(?,?,?)"
    );

    $status = "Pending";

    mysqli_stmt_bind_param(
        $stmt,
        "iis",
        $job_id,
        $student_id,
        $status
    );

    if (mysqli_stmt_execute($stmt)) {

        echo json_encode([
            "status" => "success",
            "message" => "Job Applied Successfully."
        ]);
    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Unable To Apply."
        ]);
    }
}
