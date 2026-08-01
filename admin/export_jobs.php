<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   CSV Download Header
=========================================== */

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=jobs_report.csv");

/* ===========================================
   Output Stream
=========================================== */

$output = fopen("php://output", "w");

/* ===========================================
   CSV Header
=========================================== */

fputcsv($output, [

    "ID",
    "Company",
    "Job Title",
    "Category",
    "Job Type",
    "Vacancy",
    "Experience",
    "Qualification",
    "Skills",
    "Salary",
    "State",
    "City",
    "Last Date",
    "Status"

]);

/* ===========================================
   Fetch Jobs
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT

        j.*,

        c.company_name

     FROM jobs j

     INNER JOIN companies c
     ON j.company_id = c.id

     ORDER BY j.id DESC"

);

/* ===========================================
   CSV Data
=========================================== */

while ($job = mysqli_fetch_assoc($query)) {

    fputcsv($output, [

        $job['id'],
        $job['company_name'],
        $job['job_title'],
        $job['category'],
        $job['job_type'],
        $job['vacancy'],
        $job['experience'],
        $job['qualification'],
        $job['skills'],
        $job['salary'],
        $job['state'],
        $job['city'],
        $job['last_date'],
        $job['status']

    ]);
}

fclose($output);
exit;
