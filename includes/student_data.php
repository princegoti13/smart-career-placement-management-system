<?php

/** @var mysqli $conn */
$id = $_SESSION['student_id'];

$result = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$student = mysqli_fetch_assoc($result);

$photo = !empty($student['profile_photo'])
    ? "../assets/uploads/profiles/" . $student['profile_photo']
    : "../assets/images/default-user.png";
