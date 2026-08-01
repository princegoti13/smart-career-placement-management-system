<?php
/*
|--------------------------------------------------------------------------
| Smart Career & Placement Management System
|--------------------------------------------------------------------------
| Common Functions
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Clean User Input
|--------------------------------------------------------------------------
*/
function cleanInput($data)
{
    global $conn;

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);

    return $data;
}

/*
|--------------------------------------------------------------------------
| Redirect Function
|--------------------------------------------------------------------------
*/
function redirect($page)
{
    header("Location: " . $page);
    exit();
}

/*
|--------------------------------------------------------------------------
| Check Empty Field
|--------------------------------------------------------------------------
*/
function isEmpty($value)
{
    return empty(trim($value));
}

/*
|--------------------------------------------------------------------------
| Check Email
|--------------------------------------------------------------------------
*/
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/*
|--------------------------------------------------------------------------
| Check Mobile Number
|--------------------------------------------------------------------------
*/
function isValidPhone($phone)
{
    return preg_match('/^[0-9]{10}$/', $phone);
}

/*
|--------------------------------------------------------------------------
| Upload Image
|--------------------------------------------------------------------------
*/
function uploadImage($file, $folder)
{
    $imageName = time() . "_" . basename($file['name']);
    $target = $folder . $imageName;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $imageName;
    }

    return false;
}

/*
|--------------------------------------------------------------------------
| Upload Resume
|--------------------------------------------------------------------------
*/
function uploadResume($file, $folder)
{
    $resumeName = time() . "_" . basename($file['name']);
    $target = $folder . $resumeName;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return $resumeName;
    }

    return false;
}

/*
|--------------------------------------------------------------------------
| Get Current Date
|--------------------------------------------------------------------------
*/
function currentDate()
{
    return date("d M Y");
}

/*
|--------------------------------------------------------------------------
| Get Current Date & Time
|--------------------------------------------------------------------------
*/
function currentDateTime()
{
    return date("d M Y h:i A");
}
