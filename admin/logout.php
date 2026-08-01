<?php
require_once('../includes/session.php');

/* Admin Session Destroy */

unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);

/* Destroy All Sessions */

session_destroy();

/* Prevent Browser Cache */

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/* Redirect To Login */

header("Location: ../index.php");
exit;
