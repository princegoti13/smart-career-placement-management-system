<?php
/*
|--------------------------------------------------------------------------
| Smart Career & Placement Management System
|--------------------------------------------------------------------------
| Session File
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

function checkStudent()
{
    if (!isset($_SESSION['student_id'])) {
        redirect("../student/login.php");
    }
}

function checkCompany()
{
    if (!isset($_SESSION['company_id'])) {
        redirect("../company/login.php");
    }
}

function checkAdmin()
{
    if (!isset($_SESSION['admin_id'])) {
        redirect("../admin/login.php");
    }
}

function logout()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    header("Location: ../index.php");
    exit;
}