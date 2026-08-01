<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkAdmin();

if (!isset($_GET['id'])) {

    header("Location: manage_companies.php");
    exit;
}

$id = (int)$_GET['id'];

/* ===========================================
   Fetch Company
=========================================== */

$query = mysqli_query(

    $conn,

    "SELECT *
     FROM companies
     WHERE id='$id'
     LIMIT 1"

);

if (mysqli_num_rows($query) == 0) {

    header("Location: manage_companies.php");
    exit;
}

$company = mysqli_fetch_assoc($query);

$error = "";
$success = "";

/* ===========================================
   Update Company
=========================================== */

if (isset($_POST['update_company'])) {

    $company_name  = cleanInput($_POST['company_name']);
    $email         = cleanInput($_POST['email']);
    $phone         = cleanInput($_POST['phone']);
    $website       = cleanInput($_POST['website']);

    $industry      = cleanInput($_POST['industry']);

    $address       = cleanInput($_POST['address']);
    $city          = cleanInput($_POST['city']);
    $state         = cleanInput($_POST['state']);
    $pincode       = cleanInput($_POST['pincode']);

    $about_company = cleanInput($_POST['about_company']);

    $account_status = cleanInput($_POST['account_status']);

    /* ===========================================
       Company Logo Upload
    =========================================== */

    $company_logo = $company['company_logo'];

    if (!empty($_FILES['company_logo']['name'])) {

        $imageName = time() . "_" . basename($_FILES['company_logo']['name']);

        $target = "../assets/uploads/company/" . $imageName;

        if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $target)) {

            if (
                !empty($company['company_logo']) &&
                $company['company_logo'] != "default-company.png"
            ) {

                $oldLogo = "../assets/uploads/company/" . $company['company_logo'];

                if (file_exists($oldLogo)) {

                    unlink($oldLogo);
                }
            }

            $company_logo = $imageName;
        }
    }

    /* ===========================================
       Update Query
    =========================================== */

    $sql = "UPDATE companies SET

            company_name=?,
            email=?,
            phone=?,
            website=?,
            industry=?,
            address=?,
            city=?,
            state=?,
            pincode=?,
            about_company=?,
            company_logo=?,
            account_status=?

            WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ssssssssssssi",

        $company_name,
        $email,
        $phone,
        $website,
        $industry,
        $address,
        $city,
        $state,
        $pincode,
        $about_company,
        $company_logo,
        $account_status,
        $id

    );

    if (mysqli_stmt_execute($stmt)) {

        $success = "Company Updated Successfully. Redirecting To Manage Companies...";

        $query = mysqli_query(

            $conn,

            "SELECT *
             FROM companies
             WHERE id='$id'
             LIMIT 1"

        );

        $company = mysqli_fetch_assoc($query);

        header("Refresh:3;url=manage_companies.php");
    } else {

        $error = "Something Went Wrong.";
    }
}

$page_title = "Edit Company";
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

                    <form
                        method="POST"
                        enctype="multipart/form-data">

                        <div class="row">

                            <!-- ===========================================
                     Company Information
                =========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-building me-2"></i>

                                            Company Information

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <!-- Company Logo -->

                                            <div class="col-md-3 text-center">

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
                                                    class="profile-image mb-3 py-2"
                                                    alt="Company Logo">

                                                <input
                                                    type="file"
                                                    name="company_logo"
                                                    class="form-control py-2">

                                            </div>

                                            <!-- Company Details -->

                                            <div class="col-md-9">

                                                <div class="row">

                                                    <!-- Company Name -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Company Name

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="company_name"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['company_name']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Email -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Email Address

                                                        </label>

                                                        <input
                                                            type="email"
                                                            name="email"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['email']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Phone -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Phone Number

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="phone"
                                                            maxlength="10"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['phone']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Website -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Website

                                                        </label>

                                                        <input
                                                            type="url"
                                                            name="website"
                                                            class="form-control"
                                                            placeholder="https://example.com"
                                                            value="<?php echo htmlspecialchars($company['website']); ?>">

                                                    </div>

                                                    <!-- Industry -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Industry

                                                        </label>

                                                        <select
                                                            name="industry"
                                                            class="form-select"
                                                            required>

                                                            <?php

                                                            $industries = [

                                                                "Software",
                                                                "IT Services",
                                                                "Web Development",
                                                                "Mobile App Development",
                                                                "Digital Marketing",
                                                                "Finance",
                                                                "Banking",
                                                                "Education",
                                                                "Healthcare",
                                                                "Manufacturing",
                                                                "E-Commerce",
                                                                "Telecommunication",
                                                                "Other"

                                                            ];

                                                            foreach ($industries as $industry) {

                                                            ?>

                                                                <option
                                                                    value="<?php echo $industry; ?>"
                                                                    <?php if ($company['industry'] == $industry) echo "selected"; ?>>

                                                                    <?php echo $industry; ?>

                                                                </option>

                                                            <?php } ?>

                                                        </select>

                                                    </div>

                                                    <!-- State -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            State

                                                        </label>

                                                        <select
                                                            name="state"
                                                            class="form-select"
                                                            required>

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
                                                                    <?php if ($company['state'] == $state) echo "selected"; ?>>

                                                                    <?php echo $state; ?>

                                                                </option>

                                                            <?php } ?>

                                                        </select>

                                                    </div>

                                                    <!-- City -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            City

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="city"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['city']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Pincode -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Pincode

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="pincode"
                                                            maxlength="6"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['pincode']); ?>"
                                                            required>

                                                    </div>

                                                    <!-- Address -->

                                                    <div class="col-md-6 mb-3">

                                                        <label>

                                                            Address

                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="address"
                                                            class="form-control"
                                                            value="<?php echo htmlspecialchars($company['address']); ?>"
                                                            required>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===========================================
                     Company Details
                =========================================== -->

                            <div class="col-12">

                                <div class="card shadow-sm mb-4">

                                    <div class="card-header bg-primary text-white">

                                        <h5 class="mb-0">

                                            <i class="fas fa-file-alt me-2"></i>

                                            Company Details

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <!-- About Company -->

                                        <div class="mb-3">

                                            <label>

                                                About Company

                                            </label>

                                            <textarea
                                                name="about_company"
                                                rows="6"
                                                class="form-control"
                                                required><?php echo htmlspecialchars($company['about_company']); ?></textarea>

                                        </div>

                                        <!-- Account Status -->

                                        <div class="mb-3">

                                            <label>

                                                Account Status

                                            </label>

                                            <select
                                                name="account_status"
                                                class="form-select"
                                                required>

                                                <option
                                                    value="Active"
                                                    <?php if ($company['account_status'] == "Active") echo "selected"; ?>>

                                                    Active

                                                </option>

                                                <option
                                                    value="Inactive"
                                                    <?php if ($company['account_status'] == "Inactive") echo "selected"; ?>>

                                                    Inactive

                                                </option>

                                            </select>

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
                                        href="manage_companies.php"
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
                                        name="update_company"
                                        class="btn btn-primary">

                                        <i class="fas fa-save me-2"></i>

                                        Update Company

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

<script>
    $(document).ready(function() {

        $("input[name='company_name']").focus();

        setTimeout(function() {

            $(".alert").fadeOut(500);

        }, 3000);

    });
</script>