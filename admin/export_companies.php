<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

/* ===========================================
   CSV Download Header
=========================================== */

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=companies_report.csv");

/* ===========================================
   Output Stream
=========================================== */

$output = fopen("php://output", "w");

/* ===========================================
   CSV Header
=========================================== */

fputcsv($output, [

    "ID",
    "Company Name",
    "Email",
    "Phone",
    "Website",
    "Industry",
    "State",
    "City",
    "Address",
    "Pincode",
    "Account Status"

]);

/* ===========================================
   Fetch Companies
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT *

     FROM companies

     ORDER BY id DESC"

);

/* ===========================================
   CSV Data
=========================================== */

while ($company = mysqli_fetch_assoc($query)) {

    fputcsv($output, [

        $company['id'],
        $company['company_name'],
        $company['email'],
        $company['phone'],
        $company['website'],
        $company['industry'],
        $company['state'],
        $company['city'],
        $company['address'],
        $company['pincode'],
        $company['account_status']

    ]);
}

fclose($output);
exit;
