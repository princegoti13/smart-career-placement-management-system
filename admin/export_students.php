<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   CSV Download Header
=========================================== */

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=students_report.csv");

/* ===========================================
   Output Stream
=========================================== */

$output = fopen("php://output", "w");

/* ===========================================
   CSV Header
=========================================== */

fputcsv($output, [

    "ID",
    "Full Name",
    "Email",
    "Phone",
    "Gender",
    "Course",
    "Semester",
    "College",
    "University",
    "CGPA",
    "Skills",
    "Preferred Role",
    "State",
    "City",
    "Account Status"

]);

/* ===========================================
   Fetch Students
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT *

     FROM students

     ORDER BY id DESC"

);

/* ===========================================
   CSV Data
=========================================== */

while ($student = mysqli_fetch_assoc($query)) {

    fputcsv($output, [

        $student['id'],
        $student['full_name'],
        $student['email'],
        $student['phone'],
        $student['gender'],
        $student['course'],
        $student['semester'],
        $student['college_name'],
        $student['university'],
        $student['cgpa'],
        $student['skills'],
        $student['preferred_role'],
        $student['state'],
        $student['city'],
        $student['account_status']

    ]);
}

fclose($output);
exit;
