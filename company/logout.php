<?php
require_once('../includes/session.php');

// Company Session Destroy
unset($_SESSION['company_id']);
unset($_SESSION['company_name']);

// Destroy All Sessions
session_destroy();

// Prevent Browser Cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to Login
header("Location: ../index.php");
exit;
