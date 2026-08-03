<?php
require_once('../includes/session.php');
/** @var mysqli $conn */
checkStudent();

$where="";
$totalQuery = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total
     FROM jobs
     $where"

);

$totalJobs = mysqli_fetch_assoc($totalQuery)['total'];

$student = mysqli_fetch_assoc(

    mysqli_query(
        $conn,
        "SELECT *
         FROM students
         WHERE id='" . $_SESSION['student_id'] . "'"
    )

);

$page_title = "View Jobs";
$body_class = "student-theme";

$student_id = $_SESSION['student_id'];
$search = isset($_GET['search']) ? cleanInput($_GET['search']) : "";
$location = isset($_GET['location']) ? cleanInput($_GET['location']) : "";
$type = isset($_GET['type']) ? cleanInput($_GET['type']) : "";

$sql = "
SELECT
    j.*,
    c.company_name,
    c.company_logo,

    (
        SELECT COUNT(*)
        FROM applications a
        WHERE a.job_id = j.id
        AND a.student_id = '$student_id'
    ) AS applied

FROM jobs j

INNER JOIN companies c
ON j.company_id = c.id

WHERE j.status='Active'
";

if (!empty($search)) {

    $sql .= " AND (
        j.job_title LIKE '%$search%'
        OR c.company_name LIKE '%$search%'
        OR j.category LIKE '%$search%'
    )";
}

if (!empty($location)) {

    $sql .= " AND j.city='$location'";
}

if (!empty($type)) {

    $sql .= " AND j.job_type='$type'";
}

$sql .= " ORDER BY j.created_at DESC";

$jobs = mysqli_query($conn, $sql);
?>

<?php include('../includes/header.php'); ?>
<?php include('../includes/sidebar_student.php'); ?>

<!-- <div class="wrapper"> -->

<div class="main-content">
    <?php include('../includes/topbar.php'); ?>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="fw-bold">Find Your Dream Job</h4>

                <p class="text-muted mb-0">
                    Browse the latest job opportunities that match your skills and career goals.
                </p>

            </div>

            <div>

                <span class="badge bg-primary fs-6 p-3">

                    Total Jobs :
                    <?php echo $totalJobs; ?>

                </span>

            </div>

        </div>

        <!-- Search Card -->

        <form method="GET">

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-5">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search Job"
                                id="searchJob"
                                value="<?php echo htmlspecialchars($search); ?>">

                        </div>

                        <div class="col-md-3">

                            <select
                                class="form-select"
                                id="locationFilter"
                                name="location">

                                <option value="">All Locations</option>

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
                                        <?php if (($location ?? '') == $city) echo "selected"; ?>>

                                        <?php echo $city; ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="col-md-2">

                            <select
                                class="form-select"
                                id="typeFilter"
                                name="type">

                                <option value="">All Types</option>

                                <?php

                                $jobTypes = [

                                    "Full Time",
                                    "Part Time",
                                    "Internship",
                                    "Remote",
                                    "Hybrid",
                                    "Contract",
                                    "Freelance"

                                ];

                                foreach ($jobTypes as $jobType) {

                                ?>

                                    <option
                                        value="<?php echo $jobType; ?>"
                                        <?php if (($type ?? '') == $jobType) echo "selected"; ?>>

                                        <?php echo $jobType; ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

        </form>

        <div id="jobMessage"></div>

        <!-- Jobs will come here -->

        <?php
        if (mysqli_num_rows($jobs) > 0) {

            while ($job = mysqli_fetch_assoc($jobs)) {
        ?>

                <div class="card shadow-sm mb-4 border-0 job-card">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <!-- Company Logo -->

                            <div class="col-lg-2 text-center">

                                <?php
                                if (!empty($job['company_logo']) && file_exists("../assets/uploads/company/" . $job['company_logo'])) {
                                ?>

                                    <img
                                        src="../assets/uploads/company/<?php echo htmlspecialchars($job['company_logo']); ?>"
                                        class="img-fluid rounded-circle border"
                                        style="width:90px;height:90px;object-fit:cover;"
                                        onerror="this.src='../assets/images/company.png';">

                                <?php } else { ?>

                                    <img
                                        src="../assets/images/default-user.png"
                                        class="img-fluid rounded-circle border"
                                        style="width:90px;height:90px;object-fit:cover;">

                                <?php } ?>

                            </div>

                            <!-- Job Information -->

                            <div class="col-lg-8">

                                <h4 class="fw-bold mb-1">

                                    <?php echo htmlspecialchars($job['job_title']); ?>

                                </h4>

                                <h6 class="text-primary mb-3">

                                    <?php echo htmlspecialchars($job['company_name']); ?>

                                </h6>

                                <div class="d-flex flex-wrap gap-2">

                                    <span class="badge bg-primary">

                                        <i class="fas fa-briefcase"></i>

                                        <?php echo htmlspecialchars($job['job_type']); ?>

                                    </span>

                                    <span class="badge bg-success">

                                        <i class="fas fa-money-bill-wave"></i>

                                        ₹ <?php echo htmlspecialchars($job['salary']); ?>

                                    </span>

                                    <span class="badge bg-secondary">

                                        <i class="fas fa-map-marker-alt"></i>

                                        <?php echo htmlspecialchars($job['city']); ?>,
                                        <?php echo htmlspecialchars($job['state']); ?>

                                    </span>

                                    <span class="badge bg-info text-dark">

                                        <i class="fas fa-user-graduate"></i>

                                        <?php echo htmlspecialchars($job['experience']); ?>

                                    </span>

                                </div>

                                <p class="text-muted mt-3 mb-0">

                                    <?php echo substr(strip_tags($job['job_description']), 0, 150); ?>...

                                </p>

                            </div>

                            <!-- Buttons -->

                            <div class="col-lg-2 text-end">

                                <button
                                    type="button"
                                    class="btn btn-outline-primary mb-2 w-100 viewDetailsBtn"
                                    data-bs-target="#job<?php echo $job['id']; ?>">

                                    <i class="fas fa-eye"></i>

                                    <span class="btn-text">View Details</span>

                                </button>

                                <?php if ($job['applied'] > 0) { ?>

                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-check"></i>
                                        Applied
                                    </button>

                                <?php } else { ?>

                                    <button
                                        class="btn btn-success w-100 applyJobBtn"
                                        data-job="<?php echo $job['id']; ?>">

                                        <i class="fas fa-paper-plane"></i>
                                        Apply Now

                                    </button>

                                <?php } ?>

                            </div>

                        </div>

                        <!-- Collapse Starts -->

                        <div
                            class="collapse mt-4"
                            id="job<?php echo $job['id']; ?>">

                            <hr>

                            <!-- Details Part-3 માં આવશે -->

                            <div class="row">

                                <!-- Left Side -->

                                <div class="col-lg-6">

                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <th width="35%">Category</th>
                                            <td><?php echo htmlspecialchars($job['category']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Job Type</th>
                                            <td><?php echo htmlspecialchars($job['job_type']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Vacancies</th>
                                            <td><?php echo htmlspecialchars($job['vacancy']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Experience</th>
                                            <td><?php echo htmlspecialchars($job['experience']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Salary</th>
                                            <td>
                                                <span class="badge bg-success">
                                                    ₹ <?php echo htmlspecialchars($job['salary']); ?>
                                                </span>
                                            </td>
                                        </tr>

                                    </table>

                                </div>

                                <!-- Right Side -->

                                <div class="col-lg-6">

                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <th width="35%">Location</th>
                                            <td><?php echo htmlspecialchars($job['city']); ?>,
                                                <?php echo htmlspecialchars($job['state']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Eligibility</th>
                                            <td><?php echo htmlspecialchars($job['qualification']); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Last Date</th>
                                            <td>

                                                <span class="badge bg-warning text-dark">

                                                    <?php echo date("d M Y", strtotime($job['last_date'])); ?>

                                                </span>

                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>
                                            <td>

                                                <?php
                                                if (trim(strtolower($job['status'])) == "active") {
                                                    echo '<span class="badge bg-success">Active</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">Closed</span>';
                                                }
                                                ?>

                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Company</th>
                                            <td><?php echo htmlspecialchars($job['company_name']); ?></td>
                                        </tr>

                                    </table>

                                </div>

                            </div>

                            <hr>

                            <h5 class="fw-bold mb-3">

                                <i class="fas fa-code text-primary"></i>

                                Required Skills

                            </h5>

                            <?php

                            $skills = explode(",", $job['skills']);

                            foreach ($skills as $skill) {

                            ?>

                                <span class="badge bg-primary me-2 mb-2 p-2">

                                    <?php echo trim($skill); ?>

                                </span>

                            <?php } ?>

                            <hr>

                            <h5 class="fw-bold mb-3">

                                <i class="fas fa-file-alt text-primary"></i>

                                Job Description

                            </h5>

                            <p class="text-muted lh-lg">

                                <?php echo nl2br(htmlspecialchars($job['job_description'])); ?>

                            </p>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">

                                <small class="text-muted">

                                    <i class="fas fa-calendar"></i>

                                    Posted On :
                                    <?php echo date("d M Y", strtotime($job['created_at'])); ?>

                                </small>

                                <?php

                                if (strtotime($job['last_date']) < strtotime(date("Y-m-d"))) {

                                ?>

                                    <button
                                        class="btn btn-danger w-100"
                                        disabled>

                                        <i class="fas fa-times"></i>

                                        Application Closed

                                    </button>

                                <?php

                                } elseif ($job['applied'] > 0) {

                                ?>

                                <?php

                                } else {

                                ?>



                                <?php } ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php
            }
        } else {
            ?>

            <div class="card shadow-sm">

                <div class="card-body text-center py-5">

                    <i class="fas fa-briefcase fa-5x text-secondary mb-3"></i>

                    <h4>

                        No Jobs Available

                    </h4>

                    <p class="text-muted">

                        There Are No Active Jobs At The Moment.

                    </p>

                </div>

            </div>

        <?php } ?>

    </div>
    <?php include('../includes/footer_student.php'); ?>
</div>

<!-- </div> -->



<script>
    $(document).on("click", ".applyJobBtn", function() {

        var button = $(this);
        var job_id = button.data("job");

        $.ajax({
            url: "ajax/apply_job.php",
            type: "POST",
            data: {
                job_id: job_id
            },
            dataType: "json",
            success: function(response) {

                if (response.status == "success") {

                    $("#jobMessage").html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );

                    button
                        .removeClass("btn-success")
                        .addClass("btn-secondary")
                        .html('<i class="fas fa-check"></i> Applied')
                        .prop("disabled", true);

                } else {

                    $("#jobMessage").html(
                        '<div class="alert alert-danger">' + response.message + '</div>'
                    );

                }

                // Success અને Error બંને માટે Message 3 સેકન્ડ પછી Hide થશે
                setTimeout(function() {

                    $("#jobMessage").fadeOut(1000, function() {
                        $(this).html("").show();
                    });

                }, 3000);

            }
        });

    });

    $(document).on("click", ".viewDetailsBtn", function() {

        var button = $(this);
        var target = $(button.data("bs-target"));

        target.collapse("toggle");

        target.on("shown.bs.collapse", function() {
            button.find(".btn-text").text("Hide Details");
        });

        target.on("hidden.bs.collapse", function() {
            button.find(".btn-text").text("View Details");
        });

    });
</script>
<script>
    $(document).ready(function() {

        function filterJobs() {

            var search = $("#searchJob").val().toLowerCase();

            var location = $("#locationFilter").val().toLowerCase();

            var type = $("#typeFilter").val().toLowerCase();

            $(".job-card").each(function() {

                var text = $(this).text().toLowerCase();

                var matchSearch = text.indexOf(search) > -1;

                var matchLocation = (location == "") || (text.indexOf(location) > -1);

                var matchType = (type == "") || (text.indexOf(type) > -1);

                if (matchSearch && matchLocation && matchType) {

                    $(this).show();

                } else {

                    $(this).hide();

                }

            });

        }

        $("#searchJob").on("keyup", filterJobs);

        $("#locationFilter").on("change", filterJobs);

        $("#typeFilter").on("change", filterJobs);

    });
</script>