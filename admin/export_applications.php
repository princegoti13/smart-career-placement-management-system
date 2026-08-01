<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   CSV Download Header
=========================================== */

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=applications_report.csv");

/* ===========================================
   Output Stream
=========================================== */

$output = fopen("php://output", "w");

/* ===========================================
   CSV Header
=========================================== */

fputcsv($output, [

    "Application ID",
    "Student Name",
    "Student Email",
    "Phone",
    "Company",
    "Job Title",
    "Status",
    "Applied Date"

]);

/* ===========================================
   Fetch Applications
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT

        a.id,
        a.status,
        a.application_date,

        s.full_name,
        s.email,
        s.phone,

        c.company_name,

        j.job_title

     FROM applications a

     INNER JOIN students s
     ON a.student_id = s.id

     INNER JOIN jobs j
     ON a.job_id = j.id

     INNER JOIN companies c
     ON j.company_id = c.id

     ORDER BY a.id DESC"

);

/* ===========================================
   CSV Data
=========================================== */

while ($app = mysqli_fetch_assoc($query)) {

    fputcsv($output, [

        $app['id'],
        $app['full_name'],
        $app['email'],
        $app['phone'],
        $app['company_name'],
        $app['job_title'],
        $app['status'],
        $app['application_date']

    ]);
}

fclose($output);
exit;
