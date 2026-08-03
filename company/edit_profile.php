<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

$id = $_SESSION['company_id'];

$query = mysqli_query($conn, "SELECT * FROM companies WHERE id='$id'");
$company = mysqli_fetch_assoc($query);

if (
    !empty($company['company_logo']) &&
    $company['company_logo'] != "default-company.png"
) {

    $logo = "../assets/uploads/company/" . $company['company_logo'];
} else {

    $logo = "../assets/images/default-company.png";
}

$error = "";
$success = "";

if (isset($_POST['update_profile'])) {

    $company_name = cleanInput($_POST['company_name']);
    $website      = cleanInput($_POST['website']);
    $industry     = cleanInput($_POST['industry']);
    $address      = cleanInput($_POST['address']);
    $city         = cleanInput($_POST['city']);
    $state        = cleanInput($_POST['state']);
    $pincode      = cleanInput($_POST['pincode']);
    $about_company = cleanInput($_POST['about_company']);

    /* Company Logo Upload */

    $company_logo = $company['company_logo'];

    if (!empty($_FILES['company_logo']['name'])) {

        $imageName = time() . "_" . basename($_FILES['company_logo']['name']);

        $target = "../assets/uploads/company/" . $imageName;

        if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $target)) {

            $company_logo = $imageName;
        } else {

            $error = "Logo Upload Failed.";
        }
    }

    $sql = "UPDATE companies SET

            company_name=?,
            company_logo=?,
            website=?,
            industry=?,
            address=?,
            city=?,
            state=?,
            pincode=?,
            about_company=?

            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssi",
        $company_name,
        $company_logo,
        $website,
        $industry,
        $address,
        $city,
        $state,
        $pincode,
        $about_company,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        $success = "Profile Updated Successfully.";

        $query = mysqli_query($conn, "SELECT * FROM companies WHERE id='$id'");
        $company = mysqli_fetch_assoc($query);
    } else {

        $error = "Something Went Wrong.";
    }
}

$page_title = "Edit Company Profile";
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

                            <div class="col-lg-12 text-center mb-4">

                                <?php

                                if (
                                    !empty($company['company_logo']) &&
                                    $company['company_logo'] != "default-company.png"
                                ) {

                                    $logo = "../assets/uploads/company/" . $company['company_logo'];
                                } else {

                                    $logo = "../assets/images/default-company.png";
                                }

                                ?>

                                <img
                                    src="<?php echo $logo; ?>"
                                    class="profile-image mb-3"
                                    alt="Company Logo">

                                <br>

                                <label class="btn btn-outline-success">

                                    <i class="fas fa-camera"></i>

                                    Change Logo

                                    <input
                                        type="file"
                                        name="company_logo"
                                        class="d-none"
                                        accept="image/*"
                                        onchange="previewImage(this,'previewImage')">

                                </label>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Company Name <span class="text-danger">*</span></label>

                                <input
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($company['company_name']); ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Email Address</label>

                                <input
                                    type="email"
                                    class="form-control bg-light"
                                    value="<?php echo htmlspecialchars($company['email']); ?>"
                                    readonly>

                                <small class="text-muted">

                                    Email Cannot Be Changed.

                                </small>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Phone Number</label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="<?php echo htmlspecialchars($company['phone']); ?>"
                                    readonly>

                                <small class="text-muted">

                                    Phone Number Cannot Be Changed.

                                </small>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Website</label>

                                <input
                                    type="url"
                                    name="website"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($company['website'] ?? ''); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Industry</label>

                                <input
                                    type="text"
                                    name="industry"
                                    class="form-control"
                                    value="<?php echo htmlspecialchars($company['industry'] ?? ''); ?>">

                            </div>

                            <!-- ===========================================
Address Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-map-marker-alt"></i>

                                            Address Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- City -->

                                            <div class="col-md-4 mb-3">

                                                <label>City</label>

                                                <select
                                                    name="city"
                                                    id="city"
                                                    class="form-select">

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
                                                        "Other"

                                                    ];

                                                    foreach ($cities as $city) {
                                                    ?>

                                                        <option
                                                            value="<?php echo $city; ?>"
                                                            <?php if (($company['city'] ?? '') == $city) echo "selected"; ?>>

                                                            <?php echo $city; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- State -->

                                            <div class="col-md-4 mb-3">

                                                <label>State</label>

                                                <select
                                                    name="state"
                                                    id="state"
                                                    class="form-select"
                                                    value="<?php echo htmlspecialchars($company['state'] ?? ''); ?>">

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
                                                        "Delhi"
                                                    ];

                                                    foreach ($states as $state) {
                                                    ?>

                                                        <option
                                                            value="<?php echo $state; ?>"
                                                            <?php if (($company['state'] ?? '') == $state) echo "selected"; ?>>

                                                            <?php echo $state; ?>

                                                        </option>

                                                    <?php } ?>

                                                </select>

                                            </div>

                                            <!-- Pincode -->

                                            <div class="col-md-4 mb-3">

                                                <label>Pincode</label>

                                                <input
                                                    type="text"
                                                    name="pincode"
                                                    maxlength="6"
                                                    onkeypress="onlyNumber(event)"
                                                    class="form-control"
                                                    value="<?php echo htmlspecialchars($company['pincode'] ?? ''); ?>">

                                            </div>

                                            <!-- Address -->

                                            <div class="col-12 mb-3">

                                                <label>Address</label>

                                                <textarea
                                                    name="address"
                                                    rows="4"
                                                    class="form-control"><?php echo htmlspecialchars($company['address'] ?? ''); ?></textarea>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
About Company
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-building"></i>

                                            About Company

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <label>Company Description</label>

                                        <textarea
                                            name="about_company"
                                            rows="6"
                                            class="form-control"
                                            placeholder="Write About Your Company..."><?php echo htmlspecialchars($company['about_company'] ?? ''); ?></textarea>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
Account Information
=========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-success text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-user-shield"></i>

                                            Account Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Account Status -->

                                            <div class="col-md-4 mb-3">

                                                <label>Account Status</label>

                                                <input
                                                    type="text"
                                                    class="form-control bg-light"
                                                    value="<?php echo htmlspecialchars($company['account_status']); ?>"
                                                    readonly>

                                            </div>

                                            <!-- Created At -->

                                            <div class="col-md-6 mb-3">

                                                <label>Registered On</label>

                                                <input
                                                    type="text"
                                                    class="form-control bg-light"
                                                    value="<?php echo date('d M Y', strtotime($company['created_at'])); ?>"
                                                    readonly>

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
                                        class="btn btn-success">

                                        <i class="fas fa-save"></i>

                                        Update Profile

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