<?php
require_once('../../includes/session.php');
/** @var mysqli $conn */

checkStudent();

header('Content-Type: application/json');

$id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $skill_name  = cleanInput($_POST['skill_name']);
    $skill_level = cleanInput($_POST['skill_level']);

    // Check Duplicate Skill
    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM student_skills WHERE student_id=? AND skill_name=?"
    );

    mysqli_stmt_bind_param($check, "is", $id, $skill_name);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {

        echo json_encode([
            "status" => "error",
            "message" => "Skill Already Added."
        ]);

        exit;
    }

    // Insert Skill
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO student_skills(student_id,skill_name,skill_level)
         VALUES(?,?,?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $id,
        $skill_name,
        $skill_level
    );

    if (mysqli_stmt_execute($stmt)) {

        echo json_encode([
            "status" => "success",
            "message" => "Skill Added Successfully."
        ]);
    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Unable To Add Skill."
        ]);
    }
}
