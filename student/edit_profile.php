<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkStudent();

$id = $_SESSION['student_id'];

$query = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$student = mysqli_fetch_assoc($query);
// $photo = !empty($student['profile_photo'])
//     ? "../assets/uploads/profiles/" . $student['profile_photo']
//     : "../assets/images/default-user.png";

if (
    !empty($student['profile_photo']) &&
    $student['profile_photo'] != "default-user.png"
) {

    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
} else {

    $photo = "../assets/images/default-user.png";
}

$error = "";
$success = "";

if (isset($_POST['update_profile'])) {
    $full_name = cleanInput($_POST['full_name']);
    $gender = cleanInput($_POST['gender']);
    $dob = $_POST['dob'];
    $address = cleanInput($_POST['address']);
    $city = cleanInput($_POST['city']);
    $state = cleanInput($_POST['state']);
    $country = cleanInput($_POST['country']);
    $pincode = cleanInput($_POST['pincode']);

    $course = cleanInput($_POST['course']);
    $semester = cleanInput($_POST['semester']);
    $college_name = cleanInput($_POST['college_name']);
    $university = cleanInput($_POST['university']);
    $passing_year = cleanInput($_POST['passing_year']);
    $cgpa = cleanInput($_POST['cgpa']);

    $linkedin = cleanInput($_POST['linkedin']);
    $github = cleanInput($_POST['github']);
    $portfolio = cleanInput($_POST['portfolio']);

    $preferred_role = cleanInput($_POST['preferred_role']);
    $preferred_location = cleanInput($_POST['preferred_location']);
    $employment_type = cleanInput($_POST['employment_type']);
    $expected_salary = cleanInput($_POST['expected_salary']);


    if (isset($_POST['skills'])) {
        $skills = implode(",", $_POST['skills']);
    }

    /* Profile Image Upload */

    $profile_photo = $student['profile_photo'];

    if (!empty($_FILES['profile_photo']['name'])) {
        $imageName = time() . '_' . $_FILES['profile_photo']['name'];

        if (!empty($_FILES['profile_photo']['name'])) {

            $imageName = time() . "_" . basename($_FILES['profile_photo']['name']);

            $target = "../assets/uploads/profiles/" . $imageName;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target)) {

                $profile_photo = $imageName;
            } else {

                $error = "Photo Upload Failed.";
            }
        }

        $profile_photo = $imageName;
    }

    /* Resume Upload */

    $resume = $student['resume'];

    if (!empty($_FILES['resume']['name'])) {
        $resumeName = time() . '_' . $_FILES['resume']['name'];

        move_uploaded_file(
            $_FILES['resume']['tmp_name'],
            "../assets/uploads/resumes/" . $resumeName
        );

        $resume = $resumeName;
    }

    $sql = "UPDATE students SET

        full_name=?,
        profile_photo=?,
        gender=?,
        dob=?,
        address=?,
        city=?,
        state=?,
        country=?,
        pincode=?,

        course=?,
        semester=?,
        college_name=?,
        university=?,
        passing_year=?,
        cgpa=?,
       
        resume=?,

        linkedin=?,
        github=?,
        portfolio=?,

        preferred_role=?,
        preferred_location=?,
        employment_type=?,
        expected_salary=?

        WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssssdsssssssssi",
        $full_name,
        $profile_photo,
        $gender,
        $dob,
        $address,
        $city,
        $state,
        $country,
        $pincode,
        $course,
        $semester,
        $college_name,
        $university,
        $passing_year,
        $cgpa,
        $resume,
        $linkedin,
        $github,
        $portfolio,
        $preferred_role,
        $preferred_location,
        $employment_type,
        $expected_salary,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        $success = "Profile Updated Successfully.";

        $query = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
        $student = mysqli_fetch_assoc($query);
    } else {
        $error = "Something Went Wrong.";
    }
}

$page_title = "Edit Profile";
$body_class = "student-theme";

include('../includes/header.php');
include('../includes/sidebar_student.php');
?>

<div class="main-content">

    <?php include('../includes/topbar.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="card">

                <!-- <div class="card-header">

                    <h4>

                        Edit Profile

                    </h4>

                </div> -->

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

                    <form method="POST" enctype="multipart/form-data">

                        <div class="row">

                            <!-- Profile Image -->

                            <div class="col-lg-12 text-center mb-4">

                                <?php
                                // $image = !empty($student['profile_photo'])
                                //     ? "../assets/uploads/profiles/" . $student['profile_photo']
                                //     : "../assets/images/default-user.png";

                                if (
                                    !empty($student['profile_photo']) &&
                                    $student['profile_photo'] != "default-user.png"
                                ) {

                                    $photo = "../assets/uploads/profiles/" . $student['profile_photo'];
                                } else {

                                    $photo = "../assets/images/default-user.png";
                                }
                                ?>

                                <img
                                    src="<?php echo $photo; ?>"
                                    id="previewImage"
                                    class="profile-image mb-3"
                                    alt="Profile">

                                <br>

                                <label class="btn btn-outline-primary">

                                    <i class="fas fa-camera"></i>

                                    Change Photo

                                    <input
                                        type="file"
                                        name="profile_photo"
                                        class="d-none"
                                        accept="image/*"
                                        onchange="previewImage(this,'previewImage')">

                                </label>

                            </div>

                            <!-- Full Name -->

                            <div class="col-md-6 mb-3">

                                <label>Full Name <span class="text-danger">*</span></label>

                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($student['full_name']); ?>"
                                    required>

                            </div>

                            <!-- Email -->

                            <div class="col-md-6 mb-3">

                                <label>Email Address</label>

                                <input
                                    type="email"
                                    class="form-control bg-light"
                                    value="<?php echo htmlspecialchars($student['email']); ?>"
                                    readonly>

                                <small class="text-muted">
                                    Email Cannot Be Changed.
                                </small>

                            </div>

                            <!-- Mobile -->

                            <div class="col-md-6 mb-3">

                                <label>Mobile Number</label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="<?php echo htmlspecialchars($student['phone']); ?>"
                                    readonly>

                                <small class="text-muted">
                                    Mobile Number Cannot Be Changed.
                                </small>

                            </div>

                            <!-- Gender -->

                            <div class="col-md-6 mb-3">

                                <label>Gender</label>

                                <select
                                    name="gender"
                                    class="form-select">

                                    <option value="">Select Gender</option>

                                    <option value="Male"
                                        <?php if (($student['gender'] ?? '') == "Male") echo "selected"; ?>>
                                        Male
                                    </option>

                                    <option value="Female"
                                        <?php if (($student['gender'] ?? '') == "Female") echo "selected"; ?>>
                                        Female
                                    </option>

                                    <option value="Other"
                                        <?php if (($student['gender'] ?? '') == "Other") echo "selected"; ?>>
                                        Other
                                    </option>

                                </select>

                            </div>

                            <!-- DOB -->

                            <div class="col-md-6 mb-3">

                                <label>Date Of Birth</label>

                                <input
                                    type="date"
                                    name="dob"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">

                            </div>

                            <!-- City -->

                            <div class="col-md-6 mb-3">

                                <label>City</label>

                                <select
                                    name="city"
                                    class="form-select">

                                    <option value="">Select City</option>

                                    <?php
                                    $cities = [
                                        "Ahmedabad",
                                        "Surat",
                                        "Rajkot",
                                        "Vadodara",
                                        "Bhavnagar",
                                        "Jamnagar",
                                        "Junagadh",
                                        "Gandhinagar",
                                        "Anand",
                                        "Nadiad",
                                        "Morbi"
                                    ];

                                    foreach ($cities as $city) {
                                    ?>

                                        <option
                                            value="<?php echo $city; ?>"
                                            <?php if (($student['city'] ?? '') == $city) echo "selected"; ?>>

                                            <?php echo $city; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- State -->

                            <div class="col-md-6 mb-3">

                                <label>State</label>

                                <select
                                    name="state"
                                    class="form-select">

                                    <option value="">Select State</option>

                                    <option value="Gujarat"
                                        <?php if (($student['state'] ?? '') == "Gujarat") echo "selected"; ?>>
                                        Gujarat
                                    </option>
                                    <option value="Gujarat"
                                        <?php if (($student['state'] ?? '') == "Gujarat ok") echo "selected"; ?>>
                                        Gujarat ok
                                    </option>

                                </select>

                            </div>

                            <!-- Country -->

                            <div class="col-md-6 mb-3">

                                <label>Country</label>

                                <select
                                    name="country"
                                    class="form-select">

                                    <option value="India" selected>

                                        India

                                    </option>

                                </select>

                            </div>

                            <!-- Pincode -->

                            <div class="col-md-6 mb-3">

                                <label>Pincode</label>

                                <input
                                    type="text"
                                    name="pincode"
                                    maxlength="6"
                                    onkeypress="onlyNumber(event)"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($student['pincode'] ?? ''); ?>">

                            </div>

                            <!-- Address -->

                            <div class="col-md-12 mb-4">

                                <label>Address</label>

                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="4"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>

                            </div>

                            <!-- ===========================
Academic Information
=========================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-graduation-cap"></i>

                                            Academic Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Enrollment Number -->

                                            <div class="col-md-6 mb-3">

                                                <label>Enrollment Number</label>

                                                <input
                                                    type="text"
                                                    class="form-control bg-light"
                                                    value="<?php echo htmlspecialchars($student['enrollment_no'] ?? ''); ?>"
                                                    readonly>

                                                <small class="text-muted">

                                                    Enrollment Number Cannot Be Changed.

                                                </small>

                                            </div>

                                            <!-- Course -->

                                            <div class="col-md-6 mb-3">

                                                <label>Course</label>

                                                <select
                                                    name="course"
                                                    class="form-select">

                                                    <option value="">Select Course</option>

                                                    <?php
                                                    $courses = [
                                                        "BCA",
                                                        "BCom",
                                                        "BBA",
                                                        "BA",
                                                        "BSc",
                                                        "BTech",
                                                        "BE",
                                                        "BPharm",
                                                        "BEd",
                                                        "BHM",
                                                        "BDS",
                                                        "MBBS",
                                                        "MCA",
                                                        "MCom",
                                                        "MBA",
                                                        "MSc",
                                                        "MA",
                                                        "MTech",
                                                        "Diploma",
                                                        "Other"
                                                    ];

                                                    foreach ($courses as $course) {
                                                    ?>

                                                        <option
                                                            value="<?php echo $course; ?>"
                                                            <?php if (($student['course'] ?? '') == $course) echo "selected"; ?>>

                                                            <?php echo $course; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Semester -->

                                            <div class="col-md-6 mb-3">

                                                <label>Semester</label>

                                                <select
                                                    name="semester"
                                                    class="form-select">

                                                    <option value="">Select Semester</option>

                                                    <?php

                                                    for ($i = 1; $i <= 8; $i++) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $i; ?>"
                                                            <?php if (($student['semester'] ?? '') == $i) echo "selected"; ?>>

                                                            Semester <?php echo $i; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- College -->

                                            <div class="col-md-6 mb-3">

                                                <label>College</label>

                                                <select
                                                    name="college_name"
                                                    class="form-select">

                                                    <option value="">Select College</option>

                                                    <?php

                                                    $colleges = [

                                                        "Hansraj College, Delhi",
                                                        "Miranda House, Delhi",
                                                        "Lady Shri Ram College for Women, Delhi",
                                                        "Shri Ram College of Commerce (SRCC), Delhi",
                                                        "Fergusson College, Pune",
                                                        "Symbiosis College of Arts and Commerce, Pune",
                                                        "Sutex Bank College of Computer Applications and Science",
                                                        "Sarvajanik College of Engineering & Technology (SCET)",
                                                        "UCCC & SPBCBA & SDHG College of BCA & IT",
                                                        "St. Xavier's College, Mumbai",
                                                        "Mithibai College, Mumbai",
                                                        "Narsee Monjee College of Commerce and Economics, Mumbai",
                                                        "Jai Hind College, Mumbai",
                                                        "Madras Christian College, Chennai",
                                                        "Stella Maris College, Chennai",
                                                        "Presidency College, Chennai",
                                                        "Ethiraj College for Women, Chennai",
                                                        "PSG College of Arts and Science, Coimbatore",
                                                        "Ramakrishna Mission Vivekananda Centenary College, Kolkata",
                                                        "St. Xavier's College, Kolkata",
                                                        "Mount Carmel College, Bengaluru",
                                                        "Kristu Jayanti College, Bengaluru",
                                                        "St. Joseph's University, Bengaluru",
                                                        "Other"

                                                    ];

                                                    foreach ($colleges as $college) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $college; ?>"
                                                            <?php if (($student['college_name'] ?? '') == $college) echo "selected"; ?>>

                                                            <?php echo $college; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- University -->

                                            <div class="col-md-6 mb-3">

                                                <label>University</label>

                                                <select
                                                    name="university"
                                                    class="form-select">

                                                    <option value="">Select University</option>

                                                    <?php

                                                    $universities = [

                                                        "Gujarat Technological University (GTU)",
                                                        "Gujarat University",
                                                        "Veer Narmad South Gujarat University (VNSGU)",
                                                        "Saurashtra University",
                                                        "Maharaja Sayajirao University Of Baroda (MSU)",
                                                        "Sardar Patel University",
                                                        "Hemchandracharya North Gujarat University",
                                                        "Bhavnagar University",
                                                        "Krantiguru Shyamji Krishna Verma Kachchh University",
                                                        "Dr. Babasaheb Ambedkar Open University",
                                                        "Charotar University Of Science And Technology (CHARUSAT)",
                                                        "Ganpat University",
                                                        "Nirma University",
                                                        "Parul University",
                                                        "Pandit Deendayal Energy University (PDEU)",
                                                        "CEPT University",
                                                        "Indus University",
                                                        "Navrachana University",
                                                        "Uka Tarsadia University",
                                                        "RK University",
                                                        "Marwadi University",
                                                        "Atmiya University",
                                                        "Silver Oak University",
                                                        "Other"

                                                    ];

                                                    foreach ($universities as $university) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $university; ?>"
                                                            <?php if (($student['university'] ?? '') == $university) echo "selected"; ?>>

                                                            <?php echo $university; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Passing Year -->

                                            <div class="col-md-6 mb-3">

                                                <label>Passing Year</label>

                                                <select
                                                    name="passing_year"
                                                    class="form-select">

                                                    <option value="">Select Year</option>

                                                    <?php

                                                    for ($year = date('Y'); $year <= 2035; $year++) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $year; ?>"
                                                            <?php if (($student['passing_year'] ?? '') == $year) echo "selected"; ?>>

                                                            <?php echo $year; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- CGPA -->

                                            <div class="col-md-6 mb-3">

                                                <label>CGPA</label>

                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    max="10"
                                                    name="cgpa"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['cgpa'] ?? ''); ?>"
                                                    placeholder="Example : 8.45">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Resume -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-file-pdf"></i>

                                            Resume

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <input
                                            type="file"
                                            name="resume"
                                            class="form-control"
                                            accept=".pdf">

                                        <?php if (!empty($student['resume'])) { ?>

                                            <div class="mt-3">

                                                <a
                                                    href="../assets/uploads/resumes/<?php echo $student['resume']; ?>"
                                                    target="_blank"
                                                    class="btn btn-success">

                                                    <i class="fas fa-download"></i>

                                                    View Resume

                                                </a>

                                            </div>

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>

                            <!-- Social Links -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-link"></i>

                                            Social Links

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-4">

                                                <label>LinkedIn</label>

                                                <input
                                                    type="url"
                                                    name="linkedin"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['linkedin'] ?? ''); ?>">

                                            </div>

                                            <div class="col-md-4">

                                                <label>GitHub</label>

                                                <input
                                                    type="url"
                                                    name="github"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['github'] ?? ''); ?>">

                                            </div>

                                            <div class="col-md-4">

                                                <label>Portfolio</label>

                                                <input
                                                    type="url"
                                                    name="portfolio"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($student['portfolio'] ?? ''); ?>">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Career Preferences -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-briefcase"></i>

                                            Career Preferences

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-3">

                                                <label>Preferred Role</label>

                                                <select
                                                    name="preferred_role"
                                                    class="form-select">

                                                    <option value="">Select</option>

                                                    <?php

                                                    $roles = [

                                                        "PHP Developer",
                                                        "Frontend Developer",
                                                        "Backend Developer",
                                                        "Full Stack Developer",
                                                        "React Developer",
                                                        "Java Developer",
                                                        "Python Developer",
                                                        "Software Engineer"

                                                    ];

                                                    foreach ($roles as $role) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $role; ?>"
                                                            <?php if (($student['preferred_role'] ?? '') == $role) echo "selected"; ?>>

                                                            <?php echo $role; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <div class="col-md-3">

                                                <label>Preferred Location</label>

                                                <select
                                                    name="preferred_location"
                                                    class="form-select">

                                                    <option value="">Select</option>

                                                    <?php

                                                    $locations = [

                                                        "Ahmedabad",
                                                        "Surat",
                                                        "Rajkot",
                                                        "Vadodara",
                                                        "Remote",
                                                        "Any Location"

                                                    ];

                                                    foreach ($locations as $location) {

                                                    ?>

                                                        <option
                                                            value="<?php echo $location; ?>"
                                                            <?php if (($student['preferred_location'] ?? '') == $location) echo "selected"; ?>>

                                                            <?php echo $location; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <div class="col-md-3">

                                                <label>Employment Type</label>

                                                <select
                                                    name="employment_type"
                                                    class="form-select">

                                                    <option value="">Select</option>

                                                    <option value="Full Time">Full Time</option>
                                                    <option value="Part Time">Part Time</option>
                                                    <option value="Internship">Internship</option>
                                                    <option value="Remote">Remote</option>

                                                </select>

                                            </div>

                                            <div class="col-md-3">

                                                <label>Expected Salary</label>

                                                <select
                                                    name="expected_salary"
                                                    class="form-select">

                                                    <option value="">Select</option>

                                                    <option value="₹10,000 - ₹15,000">₹10,000 - ₹15,000</option>
                                                    <option value="₹15,000 - ₹20,000">₹15,000 - ₹20,000</option>
                                                    <option value="₹20,000 - ₹30,000">₹20,000 - ₹30,000</option>
                                                    <option value="₹30,000+">₹30,000+</option>

                                                </select>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="text-end">

                                <a href="profile.php" class="btn btn-secondary">

                                    <i class="fas fa-arrow-left"></i>

                                    Back

                                </a>

                                <button
                                    type="reset"
                                    class="btn btn-warning">

                                    Reset

                                </button>

                                <button
                                    type="submit"
                                    name="update_profile"
                                    class="btn btn-primary">

                                    <i class="fas fa-save"></i>

                                    Update Profile

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

    <?php include('../includes/footer_student.php'); ?>