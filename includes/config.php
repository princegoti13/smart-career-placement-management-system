<?php
/*
|--------------------------------------------------------------------------
| Smart Career & Placement Management System
|--------------------------------------------------------------------------
| Configuration File
|--------------------------------------------------------------------------
*/

define('SITE_NAME', 'Smart Career & Placement Management System');

define('SITE_URL', 'http://localhost/career-placement-system');

define('TIMEZONE', 'Asia/Kolkata');

date_default_timezone_set(TIMEZONE);

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
*/

define('DB_HOST', 'localhost');

define('DB_NAME', 'career_placement');

define('DB_USER', 'root');

define('DB_PASS', '');

/*
|--------------------------------------------------------------------------
| Upload Paths
|--------------------------------------------------------------------------
*/

define('PROFILE_UPLOAD', '../assets/uploads/profile/');

define('RESUME_UPLOAD', '../assets/uploads/resumes/');

define('COMPANY_LOGO_UPLOAD', '../assets/uploads/company_logo/');
