<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

if (isset($_SESSION['company_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $company_name   = cleanInput($_POST['company_name']);
    $email          = cleanInput($_POST['email']);
    $phone          = cleanInput($_POST['phone']);
    $password       = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $website        = cleanInput($_POST['website']);
    $industry       = cleanInput($_POST['industry']);
    $address        = cleanInput($_POST['address']);
    $city           = cleanInput($_POST['city']);
    $state          = cleanInput($_POST['state']);
    $pincode        = cleanInput($_POST['pincode']);
    $about_company  = cleanInput($_POST['about_company']);

    $company_logo = "";

    // Required Validation
    if (
        empty($company_name) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $error = "Please Fill All Required Fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid Email Address.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = "Phone Number Must Be 10 Digits.";
    } elseif (strlen($password) < 6) {

        $error = "Password Must Be At Least 6 Characters.";
    } elseif ($password != $confirmPassword) {

        $error = "Passwords Do Not Match.";
    } else {

        // Email Exists
        $check = mysqli_query($conn, "SELECT id FROM companies WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {

            $error = "Email Already Registered.";
        } else {

            // Upload Logo
            if (!empty($_FILES['company_logo']['name'])) {

                $ext = strtolower(pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION));

                $allowed = ['jpg', 'jpeg', 'png'];

                if (!in_array($ext, $allowed)) {

                    $error = "Only JPG, JPEG And PNG Files Allowed.";
                } else {

                    if ($_FILES['company_logo']['size'] > 2 * 1024 * 1024) {

                        $error = "Logo Size Must Be Less Than 2MB.";
                    } else {

                        $company_logo = time() . "_" . rand(1000, 9999) . "." . $ext;

                        move_uploaded_file(
                            $_FILES['company_logo']['tmp_name'],
                            "../uploads/company/" . $company_logo
                        );
                    }
                }
            }

            if (empty($error)) {

                $password = password_hash($password, PASSWORD_DEFAULT);

                $status = "Active";

                $sql = "INSERT INTO companies
                (
                    company_name,
                    email,
                    phone,
                    password,
                    company_logo,
                    website,
                    industry,
                    address,
                    city,
                    state,
                    pincode,
                    about_company,
                    account_status
                )
                VALUES
                (
                    '$company_name',
                    '$email',
                    '$phone',
                    '$password',
                    '$company_logo',
                    '$website',
                    '$industry',
                    '$address',
                    '$city',
                    '$state',
                    '$pincode',
                    '$about_company',
                    '$status'
                )";

                if (mysqli_query($conn, $sql)) {

                    $success = "Registration Successful. Redirecting To Login...";

                    header("refresh:2;url=login.php");
                } else {

                    $error = "Something Went Wrong.";
                }
            }
        }
    }
}
?>

<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/company.css">


<div class="container py-5">

    <div class="row justify-content-center company-register-header">

        <div class="col-lg-10">

            <div class="card shadow border-0 rounded-4">

                <div class="auth-logo">

                    <div class="company-register-icon center">
                        <i class="fas fa-building-user"></i>
                    </div>

                    <h3>Company Registration</h3>

                    <p class="text-muted">
                        Create Your Company Account
                    </p>

                </div>

                <?php if (!empty($error)) { ?>

                    <div class="alert alert-danger">

                        <?php echo $error; ?>

                    </div>

                <?php } ?>

                <?php if (!empty($success)) { ?>

                    <div class="alert alert-success">

                        <?php echo $success; ?>

                    </div>

                <?php } ?>

                <div class="card-body p-4">

                    <form action="" method="POST" enctype="multipart/form-data">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Company Name <span class="text-danger">*</span></label>

                                <input
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    placeholder="Enter Company Name"
                                    value="<?php echo isset($company_name) ? htmlspecialchars($company_name) : ''; ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Company Logo</label>

                                <input
                                    type="file"
                                    name="company_logo"
                                    class="form-control py-2"
                                    accept=".jpg,.jpeg,.png">

                                <!-- <img
                                    id="logoPreview"
                                    src="../assets/images/company.png"
                                    class="mt-3 rounded border"
                                    style="width:120px;height:120px;object-fit:cover;"> -->

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Email Address <span class="text-danger">*</span></label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Enter Email Address"
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    maxlength="10"
                                    placeholder="Enter Phone Number"
                                    value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Password <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Enter Password"
                                        required>

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        id="togglePassword">

                                        <i class="fas fa-eye"></i>

                                    </button>

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        name="confirm_password"
                                        id="confirmPassword"
                                        class="form-control"
                                        placeholder="Confirm Password"
                                        required>

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        id="toggleConfirmPassword">

                                        <i class="fas fa-eye"></i>

                                    </button>

                                </div>
                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Website</label>

                                <input
                                    type="url"
                                    name="website"
                                    class="form-control"
                                    value="<?php echo isset($website) ? htmlspecialchars($website) : ''; ?>"
                                    placeholder="https://example.com">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Industry</label>

                                <input
                                    type="text"
                                    name="industry"
                                    class="form-control"
                                    value="<?php echo isset($industry) ? htmlspecialchars($industry) : ''; ?>"
                                    placeholder="Enter Industry">

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label">Address</label>

                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter Company Address"><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></textarea>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">City</label>

                                <input
                                    type="text"
                                    name="city"
                                    class="form-control"
                                    value="<?php echo isset($city) ? htmlspecialchars($city) : ''; ?>"
                                    placeholder="Enter City">

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">State</label>

                                <input
                                    type="text"
                                    name="state"
                                    class="form-control"
                                    value="<?php echo isset($state) ? htmlspecialchars($state) : ''; ?>"
                                    placeholder="Enter State">

                            </div>

                            <div class="col-md-4 mb-4">

                                <label class="form-label">Pincode</label>

                                <input
                                    type="text"
                                    name="pincode"
                                    class="form-control"
                                    maxlength="6"
                                    value="<?php echo isset($pincode) ? htmlspecialchars($pincode) : ''; ?>"
                                    placeholder="Enter Pincode">

                            </div>

                            <div class="col-12 mb-4">

                                <label class="form-label">About Company</label>

                                <textarea
                                    name="about_company"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Write About Your Company"><?php echo isset($about_company) ? htmlspecialchars($about_company) : ''; ?></textarea>

                            </div>

                            </.div>

                            <div class="d-grid">

                                <button
                                    type="submit"
                                    name="register"
                                    class="btn btn-primary btn-lg">

                                    <i class="fas fa-user-plus me-2"></i>

                                    Register Company

                                </button>

                            </div>

                            <div class="text-center mt-4">

                                Already Have An Account?

                                <a href="login.php" class="text-decoration-none fw-bold">

                                    Login Here

                                </a>

                            </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include('../includes/footer.php'); ?>

<script>
    $("#togglePassword").click(function() {

        var input = $("#password");

        if (input.attr("type") == "password") {

            input.attr("type", "text");

            $(this).find("i")
                .removeClass("fa-eye")
                .addClass("fa-eye-slash");

        } else {

            input.attr("type", "password");

            $(this).find("i")
                .removeClass("fa-eye-slash")
                .addClass("fa-eye");

        }

    });

    $("#toggleConfirmPassword").click(function() {

        var input = $("#confirmPassword");

        if (input.attr("type") == "password") {

            input.attr("type", "text");

            $(this).find("i")
                .removeClass("fa-eye")
                .addClass("fa-eye-slash");

        } else {

            input.attr("type", "password");

            $(this).find("i")
                .removeClass("fa-eye-slash")
                .addClass("fa-eye");

        }

    });

    $("input[name='company_logo']").change(function() {

        const file = this.files[0];

        if (file) {

            $("#logoPreview").attr(
                "src",
                URL.createObjectURL(file)
            );

        }

    });
</script>