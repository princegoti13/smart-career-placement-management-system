<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

$company_id = $_SESSION['company_id'];

$error = "";
$success = "";

if (isset($_POST['publish_job'])) {

    $job_title      = cleanInput($_POST['job_title']);
    $category       = cleanInput($_POST['category']);
    $job_type       = cleanInput($_POST['job_type']);
    $vacancy        = cleanInput($_POST['vacancy']);

    $experience     = cleanInput($_POST['experience']);
    $qualification  = cleanInput($_POST['qualification']);

    if (isset($_POST['skills'])) {
        $skills = implode(", ", $_POST['skills']);
    } else {
        $skills = "";
    }

    $salary             = cleanInput($_POST['salary']);
    $state              = cleanInput($_POST['state']);
    $city               = cleanInput($_POST['city']);

    $job_description    = cleanInput($_POST['job_description']);
    $responsibilities   = cleanInput($_POST['responsibilities']);
    $benefits           = cleanInput($_POST['benefits']);

    $last_date          = $_POST['last_date'];
    $status             = cleanInput($_POST['status']);

    $sql = "INSERT INTO jobs(

            company_id,
            job_title,
            category,
            job_type,
            vacancy,
            experience,
            qualification,
            skills,
            salary,
            state,
            city,
            job_description,
            responsibilities,
            benefits,
            last_date,
            status

        ) VALUES(

            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?

        )";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "isssisssssssssss",

        $company_id,
        $job_title,
        $category,
        $job_type,
        $vacancy,
        $experience,
        $qualification,
        $skills,
        $salary,
        $state,
        $city,
        $job_description,
        $responsibilities,
        $benefits,
        $last_date,
        $status

    );

    if (mysqli_stmt_execute($stmt)) {

        $success = "Job Posted Successfully.";
    } else {

        $error = "Something Went Wrong.";
    }
}

$page_title = "Post Job";
$body_class = "company-theme";

include('../includes/header.php');
include('../includes/sidebar_company.php');
?>

<link rel="stylesheet" href="../assets/css/company.css">
<div class="main-content">

    <?php include('../includes/topbar_company.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-body">

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>

                    <?php if ($success != "") { ?>

                        <div class="alert alert-success">

                            <?php echo $success; ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="row">

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-briefcase"></i>

                                            Job Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Job Title -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Job Title
                                                    <span class="text-danger">*</span>

                                                </label>

                                                <input
                                                    type="text"
                                                    name="job_title"
                                                    class="form-control"
                                                    placeholder="Enter Job Title"
                                                    required>

                                            </div>

                                            <!-- Category -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Job Category

                                                </label>

                                                <select
                                                    name="category"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select Category</option>

                                                    <option>Software Development</option>
                                                    <option>Web Development</option>
                                                    <option>Mobile App Development</option>
                                                    <option>UI/UX Design</option>
                                                    <option>Database</option>
                                                    <option>Networking</option>
                                                    <option>Cyber Security</option>
                                                    <option>Cloud Computing</option>
                                                    <option>Data Science</option>
                                                    <option>Artificial Intelligence</option>
                                                    <option>Digital Marketing</option>
                                                    <option>Accounting</option>
                                                    <option>Sales</option>
                                                    <option>HR</option>

                                                </select>

                                            </div>

                                            <!-- Job Type -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Job Type

                                                </label>

                                                <select
                                                    name="job_type"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select Job Type</option>

                                                    <option>Full Time</option>
                                                    <option>Part Time</option>
                                                    <option>Internship</option>
                                                    <option>Contract</option>
                                                    <option>Remote</option>

                                                </select>

                                            </div>

                                            <!-- Vacancy -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Vacancy

                                                </label>

                                                <input
                                                    type="number"
                                                    name="vacancy"
                                                    min="1"
                                                    class="form-control"
                                                    placeholder="Enter Number Of Vacancies"
                                                    required>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Requirement Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-user-graduate"></i>

                                            Job Requirements

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Experience -->

                                            <div class="col-md-6 mb-3">

                                                <label>Experience</label>

                                                <select
                                                    name="experience"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select Experience</option>

                                                    <option>Fresher</option>
                                                    <option>0 - 1 Year</option>
                                                    <option>1 - 2 Years</option>
                                                    <option>2 - 3 Years</option>
                                                    <option>3 - 5 Years</option>
                                                    <option>5+ Years</option>

                                                </select>

                                            </div>

                                            <!-- Qualification -->

                                            <div class="col-md-6 mb-3">

                                                <label>Qualification</label>

                                                <select
                                                    name="qualification"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select Qualification</option>

                                                    <option>BCA</option>
                                                    <option>B.Sc IT</option>
                                                    <option>B.Tech</option>
                                                    <option>MCA</option>
                                                    <option>M.Tech</option>
                                                    <option>Any Graduate</option>

                                                </select>

                                            </div>

                                            <!-- Skills -->

                                            <!-- Required Skills -->

                                            <div class="col-12 mb-3">

                                                <label class="form-label">

                                                    Required Skills

                                                </label>

                                                <div class="row">

                                                    <?php

                                                    $skills = [

                                                        "PHP",
                                                        "MySQL",
                                                        "HTML",
                                                        "CSS",
                                                        "Bootstrap",
                                                        "JavaScript",
                                                        "jQuery",
                                                        "AJAX",
                                                        "React",
                                                        "Node.js",
                                                        "Java",
                                                        "Python",
                                                        "C",
                                                        "C++",
                                                        "Flutter",
                                                        "Android",
                                                        "Laravel",
                                                        "CodeIgniter"

                                                    ];

                                                    foreach ($skills as $skill) {

                                                    ?>

                                                        <div class="col-md-3 col-sm-4 col-6 mb-2">

                                                            <div class="form-check">

                                                                <input
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    name="skills[]"
                                                                    value="<?php echo $skill; ?>"
                                                                    id="<?php echo str_replace(['+', '.', ' '], ['_', '', '_'], $skill); ?>">

                                                                <label
                                                                    class="form-check-label"
                                                                    for="<?php echo str_replace(['+', '.', ' '], ['_', '', '_'], $skill); ?>">

                                                                    <?php echo $skill; ?>

                                                                </label>

                                                            </div>

                                                        </div>

                                                    <?php } ?>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Salary & Location
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-map-marker-alt"></i>

                                            Salary & Location

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Salary -->

                                            <div class="col-md-4 mb-3">

                                                <label>Salary</label>

                                                <input
                                                    type="text"
                                                    name="salary"
                                                    class="form-control"
                                                    placeholder="Example : ₹25,000 / Month">

                                            </div>

                                            <!-- State -->

                                            <div class="col-md-4 mb-3">

                                                <label>State</label>

                                                <select
                                                    name="state"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select State</option>

                                                    <?php

                                                    $states = [

                                                        "Andhra Pradesh",
                                                        "Arunachal Pradesh",
                                                        "Assam",
                                                        "Bihar",
                                                        "Chhattisgarh",
                                                        "Goa",
                                                        "Gujarat",
                                                        "Haryana",
                                                        "Himachal Pradesh",
                                                        "Jharkhand",
                                                        "Karnataka",
                                                        "Kerala",
                                                        "Madhya Pradesh",
                                                        "Maharashtra",
                                                        "Manipur",
                                                        "Meghalaya",
                                                        "Mizoram",
                                                        "Nagaland",
                                                        "Odisha",
                                                        "Punjab",
                                                        "Rajasthan",
                                                        "Sikkim",
                                                        "Tamil Nadu",
                                                        "Telangana",
                                                        "Tripura",
                                                        "Uttar Pradesh",
                                                        "Uttarakhand",
                                                        "West Bengal",
                                                        "Delhi",
                                                        "Jammu and Kashmir",
                                                        "Ladakh",
                                                        "Chandigarh",
                                                        "Dadra and Nagar Haveli and Daman and Diu",
                                                        "Lakshadweep",
                                                        "Puducherry",
                                                        "Andaman and Nicobar Islands",
                                                        "Other"

                                                    ];

                                                    foreach ($states as $state) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $state; ?>"
                                                            <?php if (($job['state'] ?? '') == $state) echo "selected"; ?>>

                                                            <?php echo $state; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- City -->

                                            <div class="col-md-4 mb-3">

                                                <label>City</label>

                                                <select
                                                    name="city"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select City</option>

                                                    <?php

                                                    $cities = [

                                                        "Ahmedabad",
                                                        "Surat",
                                                        "Vadodara",
                                                        "Rajkot",
                                                        "Bhavnagar",
                                                        "Jamnagar",
                                                        "Junagadh",
                                                        "Gandhinagar",
                                                        "Anand",
                                                        "Nadiad",
                                                        "Morbi",
                                                        "Bharuch",
                                                        "Navsari",
                                                        "Valsad",
                                                        "Mehsana",
                                                        "Mumbai",
                                                        "Pune",
                                                        "Delhi",
                                                        "Bengaluru",
                                                        "Chennai",
                                                        "Hyderabad",
                                                        "Kolkata",
                                                        "Jaipur",
                                                        "Lucknow",
                                                        "Indore",
                                                        "Other"

                                                    ];

                                                    foreach ($cities as $city) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $city; ?>"
                                                            <?php if (($job['city'] ?? '') == $city) echo "selected"; ?>>

                                                            <?php echo $city; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Job Details
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-file-alt"></i>

                                            Job Details

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="mb-3">

                                            <label>Job Description</label>

                                            <textarea
                                                name="job_description"
                                                rows="5"
                                                class="form-control"
                                                placeholder="Write Job Description..."
                                                required></textarea>

                                        </div>

                                        <div class="mb-3">

                                            <label>Responsibilities</label>

                                            <textarea
                                                name="responsibilities"
                                                rows="4"
                                                class="form-control"
                                                placeholder="Write Responsibilities..."></textarea>

                                        </div>

                                        <div class="mb-3">

                                            <label>Benefits</label>

                                            <textarea
                                                name="benefits"
                                                rows="4"
                                                class="form-control"
                                                placeholder="Write Benefits..."></textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Application Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-calendar-alt"></i>

                                            Application Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Last Date -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Last Date To Apply
                                                    <span class="text-danger">*</span>

                                                </label>

                                                <input
                                                    type="date"
                                                    name="last_date"
                                                    class="form-control"
                                                    min="<?php echo date('Y-m-d'); ?>"
                                                    required>

                                            </div>

                                            <!-- Status -->

                                            <div class="col-md-6 mb-3">

                                                <label>

                                                    Job Status

                                                </label>

                                                <select
                                                    name="status"
                                                    class="form-select"
                                                    required>

                                                    <option value="Active" selected>

                                                        Active

                                                    </option>

                                                    <option value="Closed">

                                                        Closed

                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Buttons
=========================================== -->

                            <div class="col-12">

                                <div class="text-end">

                                    <a
                                        href="manage_jobs.php"
                                        class="btn btn-secondary">

                                        <i class="fas fa-arrow-left"></i>

                                        Back

                                    </a>

                                    <button
                                        type="reset"
                                        class="btn btn-warning">

                                        <i class="fas fa-undo"></i>

                                        Reset

                                    </button>

                                    <button
                                        type="submit"
                                        name="publish_job"
                                        class="btn btn-success">

                                        <i class="fas fa-paper-plane"></i>

                                        Publish Job

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include('../includes/footer_company.php'); ?>