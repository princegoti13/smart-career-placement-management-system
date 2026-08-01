<?php
require_once('../../includes/session.php');
/** @var mysqli $conn */
checkStudent();

header('Content-Type: application/json');

$id = $_SESSION['student_id'];

if (isset($_FILES['resume'])) {

    $file = $_FILES['resume'];

    // PDF Only
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($extension != "pdf") {

        echo json_encode([
            "status" => "error",
            "message" => "Only PDF File Is Allowed."
        ]);
        exit;
    }

    // Max 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {

        echo json_encode([
            "status" => "error",
            "message" => "Maximum File Size Is 5 MB."
        ]);
        exit;
    }

    // Get Old Resume
    $result = mysqli_query(
        $conn,
        "SELECT resume FROM students WHERE id='$id'"
    );
    $student = mysqli_fetch_assoc($result);

    // Delete Old Resume
    if (!empty($student['resume'])) {

        $oldFile = "../../assets/uploads/resumes/" . $student['resume'];

        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }

    // New File Name
    $fileName = "resume_" . $id . "_" . time() . ".pdf";

    $destination = "../../assets/uploads/resumes/" . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {

        mysqli_query(
            $conn,
            "UPDATE students SET resume='$fileName' WHERE id='$id'"
        );

        echo json_encode([
            "status" => "success",
            "message" => "Resume Uploaded Successfully."
        ]);
    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Unable To Upload Resume."
        ]);
    }
}
