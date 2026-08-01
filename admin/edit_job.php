<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

if (!isset($_GET['id'])) {

    header("Location: manage_jobs.php");
    exit;
    // die("ID Not Found");
}

$id = (int)$_GET['id'];

/* Fetch Job */

$query = mysqli_query(
    $conn,
    "SELECT *
FROM jobs
WHERE id='$id'
LIMIT 1"
);

if (mysqli_num_rows($query) == 0) {

    header("Location: manage_jobs.php");
    exit;
    // die("Job Not Found");
}

$job = mysqli_fetch_assoc($query);

$error = "";
$success = "";

if (isset($_POST['update_job'])) {

    $job_title          = cleanInput($_POST['job_title']);
    $category           = cleanInput($_POST['category']);
    $job_type           = cleanInput($_POST['job_type']);
    $vacancy            = cleanInput($_POST['vacancy']);

    $experience         = cleanInput($_POST['experience']);
    $qualification      = cleanInput($_POST['qualification']);

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

    $sql = "UPDATE jobs SET

        job_title=?,
        category=?,
        job_type=?,
        vacancy=?,
        experience=?,
        qualification=?,
        skills=?,
        salary=?,
        state=?,
        city=?,
        job_description=?,
        responsibilities=?,
        benefits=?,
        last_date=?,
        status=?

        WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "sssisssssssssssi",

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
        $status,
        $id

    );

    if (mysqli_stmt_execute($stmt)) {

        $success = "Job Updated Successfully. Redirecting To Manage Jobs...";

        $query = mysqli_query(
            $conn,
            "SELECT *
        FROM jobs
        WHERE id='$id'
        LIMIT 1"
        );

        $job = mysqli_fetch_assoc($query);

        header("refresh:3;url=manage_jobs.php");
    } else {

        $error = "Something Went Wrong.";
    }
}

$page_title = "Edit Job";
$body_class = "admin-theme";

include('../includes/header.php');
include('../includes/sidebar_admin.php');
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<div class="main-content">

    <?php include('../includes/topbar_admin.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-body">

                    <?php if ($success != "") { ?>

                        <div class="alert alert-success">

                            <i class="fas fa-check-circle me-2"></i>

                            <?php echo $success; ?>

                        </div>

                    <?php } ?>
                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">

                            <i class="fas fa-times-circle me-2"></i>

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="row">

                            <!-- ===========================================
Job Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-briefcase"></i>

                                            Job Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Job Title -->

                                            <div class="col-md-6 mb-3">

                                                <label>Job Title <span class="text-danger">*</span></label>

                                                <input
                                                    type="text"
                                                    name="job_title"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($job['job_title']); ?>"
                                                    required>

                                            </div>

                                            <!-- Category -->

                                            <div class="col-md-6 mb-3">

                                                <label>Category</label>

                                                <select
                                                    name="category"
                                                    class="form-select"
                                                    required>

                                                    <option value="">Select Category</option>

                                                    <?php

                                                    $categories = [

                                                        "Software Development",
                                                        "Web Development",
                                                        "Mobile App Development",
                                                        "UI/UX Design",
                                                        "Database",
                                                        "Networking",
                                                        "Cyber Security",
                                                        "Cloud Computing",
                                                        "Data Science",
                                                        "Artificial Intelligence",
                                                        "Digital Marketing",
                                                        "Accounting",
                                                        "Sales",
                                                        "HR"

                                                    ];

                                                    foreach ($categories as $category) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $category; ?>"
                                                            <?php if ($job['category'] == $category) echo "selected"; ?>>

                                                            <?php echo $category; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Job Type -->

                                            <div class="col-md-6 mb-3">

                                                <label>Job Type</label>

                                                <select
                                                    name="job_type"
                                                    class="form-select"
                                                    required>

                                                    <?php

                                                    $types = [

                                                        "Full Time",
                                                        "Part Time",
                                                        "Internship",
                                                        "Contract",
                                                        "Remote"

                                                    ];

                                                    foreach ($types as $type) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $type; ?>"
                                                            <?php if ($job['job_type'] == $type) echo "selected"; ?>>

                                                            <?php echo $type; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Vacancy -->

                                            <div class="col-md-6 mb-3">

                                                <label>Vacancy</label>

                                                <input
                                                    type="number"
                                                    name="vacancy"
                                                    class="form-control"
                                                    min="1"
                                                    value="<?php echo htmlspecialchars($job['vacancy']); ?>"
                                                    required>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Job Requirements
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

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

                                                    <?php

                                                    $experiences = [

                                                        "Fresher",
                                                        "0 - 1 Year",
                                                        "1 - 2 Years",
                                                        "2 - 3 Years",
                                                        "3 - 5 Years",
                                                        "5+ Years"

                                                    ];

                                                    foreach ($experiences as $experience) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $experience; ?>"
                                                            <?php if ($job['experience'] == $experience) echo "selected"; ?>>

                                                            <?php echo $experience; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Qualification -->

                                            <div class="col-md-6 mb-3">

                                                <label>Qualification</label>

                                                <select
                                                    name="qualification"
                                                    class="form-select"
                                                    required>

                                                    <?php

                                                    $qualifications = [

                                                        "BCA",
                                                        "B.Sc IT",
                                                        "B.Tech",
                                                        "MCA",
                                                        "M.Tech",
                                                        "Any Graduate"

                                                    ];

                                                    foreach ($qualifications as $qualification) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $qualification; ?>"
                                                            <?php if ($job['qualification'] == $qualification) echo "selected"; ?>>

                                                            <?php echo $qualification; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Skills -->

                                            <div class="col-12">

                                                <label class="mb-2">

                                                    Required Skills

                                                </label>

                                                <div class="row">

                                                    <?php

                                                    $selectedSkills = array_map('trim', explode(",", $job['skills']));

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

                                                        $id = str_replace(['+', '.', ' '], ['_', '', '_'], $skill);

                                                    ?>

                                                        <div class="col-md-3 col-sm-4 col-6 mb-2">

                                                            <div class="form-check">

                                                                <input
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    name="skills[]"
                                                                    id="<?php echo $id; ?>"
                                                                    value="<?php echo $skill; ?>"
                                                                    <?php if (in_array($skill, $selectedSkills)) echo "checked"; ?>>

                                                                <label
                                                                    class="form-check-label"
                                                                    for="<?php echo $id; ?>">

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

                                    <div class="card-header bg-primary text-white">

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
                                                    value="<?php echo htmlspecialchars($job['salary']); ?>"
                                                    placeholder="Example : ₹20,000 - ₹30,000"
                                                    required>

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

                                                        "Gujarat",
                                                        "Maharashtra",
                                                        "Rajasthan",
                                                        "Delhi",
                                                        "Karnataka",
                                                        "Tamil Nadu",
                                                        "Madhya Pradesh"

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
                                                        "Gandhinagar",
                                                        "Mumbai",
                                                        "Pune",
                                                        "Delhi",
                                                        "Bangalore",
                                                        "Chennai"

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

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-file-alt"></i>

                                            Job Details

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <!-- Job Description -->

                                        <div class="mb-3">

                                            <label>Job Description</label>

                                            <textarea
                                                name="job_description"
                                                class="form-control"
                                                rows="5"
                                                required><?php echo htmlspecialchars($job['job_description']); ?></textarea>

                                        </div>

                                        <!-- Responsibilities -->

                                        <div class="mb-3">

                                            <label>Responsibilities</label>

                                            <textarea
                                                name="responsibilities"
                                                class="form-control"
                                                rows="5"><?php echo htmlspecialchars($job['responsibilities'] ?? ''); ?></textarea>

                                        </div>

                                        <!-- Benefits -->

                                        <div class="mb-3">

                                            <label>Benefits</label>

                                            <textarea
                                                name="benefits"
                                                class="form-control"
                                                rows="5"><?php echo htmlspecialchars($job['benefits'] ?? ''); ?></textarea>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Application Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

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

                                                </label>

                                                <input
                                                    type="date"
                                                    name="last_date"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($job['last_date']); ?>"
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

                                                    <option
                                                        value="Active"
                                                        <?php if ($job['status'] == "Active") echo "selected"; ?>>

                                                        Active

                                                    </option>

                                                    <option
                                                        value="Closed"
                                                        <?php if ($job['status'] == "Closed") echo "selected"; ?>>

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

                                        <i class="fas fa-arrow-left me-2"></i>

                                        Back

                                    </a>

                                    <button
                                        type="reset"
                                        class="btn btn-warning">

                                        <i class="fas fa-undo me-2"></i>

                                        Reset

                                    </button>

                                    <button
                                        type="submit"
                                        name="update_job"
                                        class="btn btn-primary">

                                        <i class="fas fa-save me-2"></i>

                                        Update Job

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<?php include('../includes/footer.php'); ?>