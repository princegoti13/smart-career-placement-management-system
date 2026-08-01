<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

if (isset($_SESSION['student_id'])) {
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

        $error = "Please Enter Valid Email Address.";
    } else {

        $sql = "SELECT * FROM students WHERE email=? LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $student = mysqli_fetch_assoc($result);

            if (password_verify($password, $student['password'])) {

                $_SESSION['student_id']   = $student['id'];
                $_SESSION['student_name'] = $student['full_name'];
                $_SESSION['student_email'] = $student['email'];

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

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">

            <i class="fas fa-user-graduate"></i>

            <h3>Student Login</h3>

            <p class="text-muted">
                Smart Career & Placement Management System
            </p>

        </div>

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

            <div class="mb-3">

                <label class="form-label">Email Address</label>

                <div class="input-group">

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter Email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Password</label>

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
                class="btn btn-primary w-100"
                name="login">

                <i class="fas fa-sign-in-alt me-2"></i>

                Login

            </button>

        </form>

        <div class="auth-footer">

            <a href="forgot_password.php">

                Forgot Password?

            </a>

            <br><br>

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

    $("form").submit(function() {

        $("#loginBtn").html(

            '<span class="spinner-border spinner-border-sm me-2"></span> Logging In...'

        );

        $("#loginBtn").prop("disabled", true);

    });

    $(document).ready(function() {

        $("input[name='email']").focus();

    });

    $("input").keypress(function(e) {

        if (e.which == 13) {

            $("form").submit();

        }

    });
</script>