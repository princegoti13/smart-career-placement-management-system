<?php
require_once('../includes/session.php');
/** @var mysqli $conn */


if (isset($_SESSION['admin_id'])) {

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

        $query = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email' LIMIT 1");

        if (mysqli_num_rows($query) == 1) {

            $admin = mysqli_fetch_assoc($query);

            if (password_verify($password, $admin['password'])) {

                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_email'] = $admin['email'];

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

$page_title = "Admin Login";

?>

<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">

            <div class="admin-login-icon center">

                <i class="fas fa-user-shield"></i>

            </div>

            <h3 class="mt-3">

                Admin Login

            </h3>

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

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">

                    Email Address

                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter Email Address"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Password

                </label>

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

            <a
                href="forgot_password.php"
                class="text-decoration-none">

                Forgot Password?

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

    $(document).ready(function() {

        $("input[name='email']").focus();

    });
</script>