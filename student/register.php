<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

if (isset($_SESSION['student_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $full_name = cleanInput($_POST['full_name']);
    $email     = cleanInput($_POST['email']);
    $phone     = cleanInput($_POST['phone']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm)) {

        $error = "Please Fill All Required Fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please Enter Valid Email Address.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = "Phone Number Must Be Exactly 10 Digits.";
    } elseif (strlen($password) < 6) {

        $error = "Password Must Be At Least 6 Characters.";
    } elseif ($password != $confirm) {

        $error = "Passwords Do Not Match.";
    } else {

        $check = mysqli_prepare($conn, "SELECT id FROM students WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $error = "Email Already Registered.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Generate Enrollment Number

            do {

                $enrollment_no = "STD" . rand(1000, 9999);

                $checkEnroll = mysqli_prepare(
                    $conn,
                    "SELECT id FROM students WHERE enrollment_no=?"
                );

                mysqli_stmt_bind_param($checkEnroll, "s", $enrollment_no);

                mysqli_stmt_execute($checkEnroll);

                $resultEnroll = mysqli_stmt_get_result($checkEnroll);
            } while (mysqli_num_rows($resultEnroll) > 0);

            $profile_photo = "default-user.png";
            $sql = "INSERT INTO students(enrollment_no,full_name,email,phone,password,profile_photo)
        VALUES(?,?,?,?,?,?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $enrollment_no,
                $full_name,
                $email,
                $phone,
                $hash,
                $profile_photo
            );

            if (mysqli_stmt_execute($stmt)) {

                $success = "Registration Successful. Redirecting To Login...";

                header("refresh:3;url=login.php");
            } else {

                $error = "Something Went Wrong. Please Try Again.";
            }
        }
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-logo">

            <i class="fas fa-user-plus"></i>

            <h3>Student Registration</h3>

            <p class="text-muted">
                Create Your Student Account
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

                <label>Full Name</label>

                <input
                    type="text"
                    name="full_name"
                    class="form-control"
                    value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>"
                    placeholder="Enter Full Name"
                    required>

            </div>

            <div class="mb-3">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                    placeholder="Enter Email Address"
                    required>

            </div>

            <div class="mb-3">

                <label>Mobile Number</label>

                <input
                    type="text"
                    name="phone"
                    maxlength="10"
                    class="form-control"
                    value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                    onkeypress="onlyNumber(event)"
                    placeholder="Enter 10 Digit Mobile Number"
                    required>

            </div>

            <div class="mb-3">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter Password"
                    required>

            </div>

            <div class="mb-3">

                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    class="form-control"
                    placeholder="Confirm Password"
                    required>

            </div>

            <button
                type="submit"
                name="register"
                class="btn btn-primary w-100">

                <i class="fas fa-user-plus"></i>

                Register

            </button>

        </form>

        <div class="auth-footer">

            Already Have An Account?

            <a href="login.php">

                Login Here

            </a>

        </div>

    </div>

</div>

<?php include('../includes/footer.php'); ?>