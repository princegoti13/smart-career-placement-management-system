<?php
require_once("../includes/session.php");

// Destroy Session
session_unset();
session_destroy();

// Prevent Browser Cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect
header("Location: ../index.php");
exit;
