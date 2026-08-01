<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

if (isset($_SESSION['company_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";
$email = "";

if (isset($_POST['login'])) {


    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];


    if (empty($email) || empty($password)) {

        $error = "Please Fill All Required Fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please Enter A Valid Email Address.";
    } else {

        $query = mysqli_query($conn, "SELECT * FROM companies WHERE email='$email' LIMIT 1");

        if (mysqli_num_rows($query) == 1) {

            $company = mysqli_fetch_assoc($query);

            if ($company['account_status'] != "Active") {

                $error = "Your Account Is Inactive.";
            } elseif (password_verify($password, $company['password'])) {

                $_SESSION['company_id'] = $company['id'];
                $_SESSION['company_name'] = $company['company_name'];
                $_SESSION['company_email'] = $company['email'];
                $_SESSION['company_logo'] = $company['company_logo'];

                $success = "Login Successful! Redirecting To Dashboard...";
                header("Refresh:3; url=dashboard.php");
            } else {

                $error = "Incorrect Password.";
            }
        } else {

            $error = "Email Not Registered.";
        }
    }
}
?>

<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/company.css">


<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">

            <div class="company-register-icon center">
                <i class="fas fa-building"></i>
            </div>

            <h3 class="mt-3">Company Login</h3>

            <p class="text-muted">
                Smart Career & Placement Management System
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

        <form action="" method="POST">

            <div class="mb-3">

                <label class="form-label">

                    Email Address

                </label>

                <div class="input-group">

                    <!-- <span class="input-group-text">

                        <i class="fas fa-envelope"></i>

                    </span> -->

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter Email Address"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Password

                </label>

                <div class="input-group">

                    <!-- <span class="input-group-text">

                        <i class="fas fa-lock"></i>

                    </span> -->

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

            <button
                type="submit"
                name="login"
                id="loginBtn"
                class="btn btn-primary w-100">

                <i class="fas fa-sign-in-alt me-2"></i>
                Login
            </button>

        </form>

        <div class="text-center mt-4">

            <!-- <div class="d-flex justify-content-between mb-4"> -->

            <a href="forgot_password.php" class="text-decoration-none">

                Forgot Password?

            </a><br><br>

            <!-- </div> -->

            Don't Have An Account?

            <a href="register.php">

                Register Here

            </a>

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

    // $("form").submit(function() {

    //     $("#loginBtn").html(

    //         '<span class="spinner-border spinner-border-sm me-2"></span> Logging In...'

    //     );

    //     $("#loginBtn").prop("disabled", true);

    // });

    $(document).ready(function() {

        $("input[name='email']").focus();

    });
</script>