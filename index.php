<?php
$page_title = "Home";

include('includes/header.php');
?>

<link rel="stylesheet" href="assets/css/index.css">

<!-- ======================================
Navbar
====================================== -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">

            <i class="fas fa-graduation-cap me-2 text-warning"></i>

            SCPS

        </a>

        <button
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="navbar">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link active" href="#home">

                        Home

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#about">

                        About

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#modules">

                        Modules

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" href="#contact">

                        Contact

                    </a>

                </li>
                <li class="nav-item">

                    <a class="nav-link" href="#future">

                        Future Features

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- ======================================
Hero
====================================== -->

<section id="home" class="hero">

    <div class="container">

        <div class="row align-items-center">

            <!-- Left -->

            <div class="col-lg-6">

                <span class="hero-badge">

                    BCA Final Year Project

                </span>

                <h1>

                    Smart Career &

                    <br>

                    Placement Management System

                </h1>

                <p>

                    A Web Based Placement Portal Developed Using
                    Core PHP, MySQL, Bootstrap and JavaScript.
                    It Connects Students, Companies and
                    Administrators On One Platform.

                </p>

                <div class="hero-buttons">

                    <a
                        href="student/login.php"
                        class="btn btn-dark">

                        <i class="fas fa-user-graduate me-2"></i>

                        Student

                    </a>

                    <a
                        href="company/login.php"
                        class="btn btn-warning">

                        <i class="fas fa-building me-2"></i>

                        Company

                    </a>

                    <a
                        href="admin/login.php"
                        class="btn btn-outline-dark">

                        <i class="fas fa-user-shield me-2"></i>

                        Admin

                    </a>

                </div>

            </div>

            <!-- Right -->

            <div class="col-lg-6 text-center">

                <img

                    src="assets/images/home.png"

                    class="img-fluid home-image"

                    alt="Placement">

            </div>

        </div>

    </div>

</section>

<!-- ======================================
About
====================================== -->

<section id="about" class="about-section">

    <div class="container">

        <div class="row align-items-center">

            <!-- Left -->

            <div class="col-lg-6">

                <img
                    src="assets/images/about.png"
                    class="img-fluid about-image"
                    alt="About">

            </div>

            <!-- Right -->

            <div class="col-lg-6">

                <span class="section-tag">

                    ABOUT PROJECT

                </span>

                <h2>

                    Smart Career & Placement
                    Management System

                </h2>

                <p>

                    Smart Career & Placement Management System is a
                    web based placement portal developed using Core PHP
                    and MySQL. It provides a single platform where
                    students can apply for jobs, companies can recruit
                    candidates and administrators can manage the entire
                    placement process efficiently.

                </p>

                <div class="row mt-4">

                    <div class="col-6 mb-3">

                        <div class="mini-card">

                            <i class="fas fa-user-graduate"></i>

                            <h4>

                                Students

                            </h4>

                            <p>

                                Job Applications

                            </p>

                        </div>

                    </div>

                    <div class="col-6 mb-3">

                        <div class="mini-card">

                            <i class="fas fa-building"></i>

                            <h4>

                                Companies

                            </h4>

                            <p>

                                Recruitment

                            </p>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="mini-card">

                            <i class="fas fa-briefcase"></i>

                            <h4>

                                Jobs

                            </h4>

                            <p>

                                Opportunities

                            </p>

                        </div>

                    </div>

                    <div class="col-6">

                        <div class="mini-card">

                            <i class="fas fa-chart-line"></i>

                            <h4>

                                Reports

                            </h4>

                            <p>

                                Analytics

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ======================================
Modules
====================================== -->

<section id="modules" class="modules-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">

                OUR MODULES

            </span>

            <h2 class="section-title">

                System Modules

            </h2>

            <p class="section-desc">

                Three Separate Modules Designed For Students,
                Companies And Administrators.

            </p>

        </div>

        <div class="row">

            <!-- Student -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="module-box">

                    <div class="module-icon">

                        <i class="fas fa-user-graduate"></i>

                    </div>

                    <h4>

                        Student Module

                    </h4>

                    <p>

                        Register Account, Complete Profile,
                        Upload Resume, Search Jobs,
                        Apply Online And Track Application Status.

                    </p>

                    <a
                        href="student/login.php"
                        class="btn btn-dark rounded-pill px-4">

                        Student Login

                    </a>

                </div>

            </div>

            <!-- Company -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="module-box">

                    <div class="module-icon">

                        <i class="fas fa-building"></i>

                    </div>

                    <h4>

                        Company Module

                    </h4>

                    <p>

                        Register Company, Create Job Posts,
                        Manage Applications,
                        Shortlist Candidates
                        And Hire Students.

                    </p>

                    <a
                        href="company/login.php"
                        class="btn btn-warning rounded-pill px-4">

                        Company Login

                    </a>

                </div>

            </div>

            <!-- Admin -->

            <div class="col-lg-4 col-md-6 mx-auto mb-4">

                <div class="module-box">

                    <div class="module-icon">

                        <i class="fas fa-user-shield"></i>

                    </div>

                    <h4>

                        Admin Module

                    </h4>

                    <p>

                        Manage Students,
                        Companies,
                        Jobs,
                        Applications,
                        Reports
                        And Overall System Activities.

                    </p>

                    <a
                        href="admin/login.php"
                        class="btn btn-outline-dark rounded-pill px-4">

                        Admin Login

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ======================================
Get In Touch
====================================== -->

<section id="contact" class="contact-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">

                CONTACT

            </span>

            <h2 class="section-title">

                Get In Touch

            </h2>

            <p class="section-desc">

                Feel Free To Contact Us For Any Queries Or Support.

            </p>

        </div>

        <div class="row">

            <!-- Email -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="contact-box">

                    <div class="contact-icon">

                        <i class="fas fa-envelope"></i>

                    </div>

                    <h5>

                        Email

                    </h5>

                    <p>

                        support@scps.com

                    </p>

                </div>

            </div>

            <!-- Phone -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="contact-box">

                    <div class="contact-icon">

                        <i class="fas fa-phone"></i>

                    </div>

                    <h5>

                        Phone

                    </h5>

                    <p>

                        +91 98765 43210

                    </p>

                </div>

            </div>

            <!-- Address -->

            <div class="col-lg-4 col-md-12 mb-4">

                <div class="contact-box">

                    <div class="contact-icon">

                        <i class="fas fa-location-dot"></i>

                    </div>

                    <h5>

                        Address

                    </h5>

                    <p>

                        Surat, Gujarat, India

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ======================================
Future Features
====================================== -->

<section id="future" class="future-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">

                FUTURE ENHANCEMENTS

            </span>

            <h2 class="section-title">

                Future Features

            </h2>

            <p class="section-desc">

                The Following Features Can Be Added In Future Versions
                To Make The System More Powerful And Efficient.

            </p>

        </div>

        <div class="row">

            <!-- Email Notification -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="future-card">

                    <div class="future-icon">

                        <i class="fas fa-envelope"></i>

                    </div>

                    <h4>

                        Email Notification

                    </h4>

                    <p>

                        Automatic Email Alerts For Job Applications,
                        Job Approval, Interview Schedule
                        And Application Status Updates.

                    </p>

                </div>

            </div>

            <!-- Notification -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="future-card">

                    <div class="future-icon">

                        <i class="fas fa-bell"></i>

                    </div>

                    <h4>

                        Notification System

                    </h4>

                    <p>

                        Real-Time Notifications For Students,
                        Companies And Administrators About
                        New Activities And Updates.

                    </p>

                </div>

            </div>

            <!-- System Logs -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="future-card">

                    <div class="future-icon">

                        <i class="fas fa-file-alt"></i>

                    </div>

                    <h4>

                        System Logs

                    </h4>

                    <p>

                        Record Every Important Activity
                        Such As Login, Job Posting,
                        Application And Status Changes.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include('includes/footer_index.php'); ?>

<script>
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    window.addEventListener("scroll", () => {

        let current = "";

        sections.forEach(section => {

            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.offsetHeight;

            if (pageYOffset >= sectionTop &&
                pageYOffset < sectionTop + sectionHeight) {

                current = section.getAttribute("id");

            }

        });

        navLinks.forEach(link => {

            link.classList.remove("active");

            if (link.getAttribute("href") === "#" + current) {

                link.classList.add("active");

            }

        });

    });
</script>