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

if (isset($_POST['reset'])) {

    $email = cleanInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($password) || empty($confirm_password)) {

        $error = "Please Fill All Required Fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please Enter Valid Email Address.";
    } elseif (strlen($password) < 6) {

        $error = "Password Must Be At Least 6 Characters.";
    } elseif ($password != $confirm_password) {

        $error = "Passwords Do Not Match.";
    } else {

        $check = mysqli_prepare($conn, "SELECT id FROM students WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) == 0) {

            $error = "Email Not Registered.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $update = mysqli_prepare($conn, "UPDATE students SET password=? WHERE email=?");
            mysqli_stmt_bind_param($update, "ss", $hash, $email);

            if (mysqli_stmt_execute($update)) {

                $success = "Password Changed Successfully. Redirecting To Login...";

                header("refresh:3;url=login.php");
            } else {

                $error = "Something Went Wrong.";
            }
        }
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">

            <i class="fas fa-key"></i>

            <h3>Forgot Password</h3>

            <p class="text-muted">
                Reset Your Student Password
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

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter Registered Email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required>

            </div>

            <div class="mb-3">

                <label>New Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter New Password"
                    required>

            </div>

            <div class="mb-3">

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    class="form-control"
                    placeholder="Confirm New Password"
                    required>

            </div>

            <button
                type="submit"
                name="reset"
                class="btn btn-primary w-100">

                <i class="fas fa-save"></i>

                Reset Password

            </button>

        </form>

        <div class="auth-footer">

            <a href="login.php">

                ← Back To Login

            </a>

        </div>

    </div>

</div>

<?php include('../includes/footer.php'); ?>