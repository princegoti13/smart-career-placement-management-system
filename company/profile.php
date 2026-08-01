<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkCompany();

$id = $_SESSION['company_id'];

$query = mysqli_query($conn, "SELECT * FROM companies WHERE id='$id'");
$company = mysqli_fetch_assoc($query);

if (
    !empty($company['company_logo']) &&
    $company['company_logo'] != "default_company.png"
) {

    $logo = "../uploads/company/" . $company['company_logo'];
} else {

    $logo = "../assets/images/company.png";
}

$page_title = "Company Profile";
$body_class = "company-theme";

include('../includes/header.php');
include('../includes/sidebar_company.php');
?>

<link rel="stylesheet" href="../assets/css/company.css">

<div class="main-content">

    <?php include('../includes/topbar_company.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="row">

                <!-- Left Side -->

                <div class="col-lg-4">

                    <div class="card">

                        <div class="card-body text-center">

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

                            <h3>

                                <?php echo htmlspecialchars($company['company_name']); ?>

                            </h3>

                            <p class="text-muted">

                                <?php echo htmlspecialchars($company['email']); ?>

                            </p>

                            <hr>

                            <div class="row">

                                <div class="col-6">

                                    <h6>

                                        Industry

                                    </h6>

                                    <p>

                                        <?php echo htmlspecialchars($company['industry'] ?? 'Not Updated'); ?>

                                    </p>

                                </div>

                                <div class="col-6">

                                    <h6>

                                        Status

                                    </h6>

                                    <p>

                                        <?php echo htmlspecialchars($company['account_status']); ?>

                                    </p>

                                </div>

                            </div>

                            <a
                                href="edit_profile.php"
                                class="btn btn-success w-100 mt-3">

                                <i class="fas fa-edit"></i>

                                Edit Profile

                            </a>

                        </div>

                    </div>

                </div>

                <!-- Right Side -->

                <div class="col-lg-8">

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Company Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>

                                        Company Name

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['company_name']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>

                                        Email

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['email']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>

                                        Phone Number

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['phone']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>

                                        Website

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['website'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>

                                        Industry

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['industry'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Address Information -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Address Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-12 mb-3">

                                    <label>Address</label>

                                    <textarea
                                        class="form-control"
                                        rows="3"
                                        readonly><?php echo htmlspecialchars($company['address'] ?? 'Not Updated'); ?></textarea>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>City</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['city'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>State</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['state'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label>Pincode</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['pincode'] ?? 'Not Updated'); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- About Company -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                About Company

                            </h5>

                        </div>

                        <div class="card-body">

                            <textarea
                                class="form-control"
                                rows="6"
                                readonly><?php echo htmlspecialchars($company['about_company'] ?? 'Not Updated'); ?></textarea>

                        </div>

                    </div>

                    <!-- Account Information -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Account Information

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>Account Status</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($company['account_status']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>Registered On</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?php echo date('d M Y', strtotime($company['created_at'])); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include('../includes/footer_company.php'); ?>