<?php
require_once('../includes/session.php');
/** @var mysqli $conn */

checkStudent();

$id = $_SESSION['student_id'];

$page_title = "Skills & Resume";
$body_class = "student-theme";

/* Student Details */

$query = mysqli_query($conn, "SELECT * FROM students WHERE id='$id'");
$student = mysqli_fetch_assoc($query);

/* Student Skills */

$skills = mysqli_query($conn, "
SELECT *
FROM student_skills
WHERE student_id='$id'
ORDER BY id DESC
");

$error = "";
$success = "";

/* Add Skill */

if (isset($_POST['add_skill'])) {

    $skill_name = cleanInput($_POST['skill_name']);

    $skill_level = cleanInput($_POST['skill_level']);

    $check = mysqli_prepare(

        $conn,

        "SELECT id FROM student_skills
        WHERE student_id=? AND skill_name=?"

    );

    mysqli_stmt_bind_param(

        $check,

        "is",

        $id,

        $skill_name

    );

    mysqli_stmt_execute($check);

    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {

        $error = "Skill Already Added.";
    } else {

        $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO student_skills
            (student_id,skill_name,skill_level)
            VALUES(?,?,?)"

        );

        mysqli_stmt_bind_param(

            $stmt,

            "iss",

            $id,

            $skill_name,

            $skill_level

        );

        if (mysqli_stmt_execute($stmt)) {

            $success = "Skill Added Successfully.";

            if (mysqli_stmt_execute($stmt)) {
                $success = "Skill Added Successfully.";

                $skills = mysqli_query($conn, "
        SELECT *
        FROM student_skills
        WHERE student_id='$id'
        ORDER BY id DESC
    ");
            }
            exit;
        } else {

            $error = "Unable To Add Skill.";
        }
    }
}

/* Delete Skill */

if (isset($_GET['delete'])) {

    $skill_id = (int)$_GET['delete'];

    $stmt = mysqli_prepare(

        $conn,

        "DELETE FROM student_skills
        WHERE id=? AND student_id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $skill_id,

        $id

    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: skills_resume.php");

        exit;
    }
}

include('../includes/header.php');
include('../includes/sidebar_student.php');
?>

<div class="main-content">

    <?php include('../includes/topbar.php'); ?>

    <div class="page-content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

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

                </div>

                <!-- Skills Card -->

                <div class="col-lg-7">

                    <div class="card shadow-sm">

                        <div class="card-header d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">

                                <i class="fas fa-code text-primary"></i>

                                My Skills

                            </h4>

                            <button
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addSkill">

                                <i class="fas fa-plus"></i>

                                Add Skill

                            </button>

                        </div>

                        <!-- Skills Table -->

                        <!-- <div class="card shadow-sm mt-4"> -->

                        <div class="card-body">

                            <?php
                            if (mysqli_num_rows($skills) > 0) {
                            ?>
                                <div id="skillMessage"></div>

                                <table class="table table-bordered table-hover align-middle mb-0">

                                    <thead class="table-dark">

                                        <tr>

                                            <th width="8%">#</th>

                                            <th>Skill</th>

                                            <th>Level</th>

                                            <th width="15%">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $i = 1;

                                        while ($row = mysqli_fetch_assoc($skills)) {

                                        ?>

                                            <tr>

                                                <td><?php echo $i++; ?></td>

                                                <td>

                                                    <?php echo $row['skill_name']; ?>

                                                </td>

                                                <td>

                                                    <?php

                                                    $level = $row['skill_level'];

                                                    switch ($level) {
                                                        case "Beginner":
                                                            echo "<span class='badge bg-secondary'>Beginner</span>";
                                                            break;

                                                        case "Intermediate":
                                                            echo "<span class='badge bg-warning text-dark'>Intermediate</span>";
                                                            break;

                                                        case "Advanced":
                                                            echo "<span class='badge bg-primary'>Advanced</span>";
                                                            break;

                                                        case "Expert":
                                                            echo "<span class='badge bg-success'>Expert</span>";
                                                            break;

                                                        default:
                                                            echo "<span class='badge bg-dark'>$level</span>";
                                                    }

                                                    ?>

                                                </td>

                                                <td>

                                                    <a
                                                        href="?delete=<?php echo $row['id']; ?>"
                                                        class="btn btn-sm btn-danger">

                                                        <i class="fas fa-trash"></i>

                                                    </a>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            <?php
                            } else {
                            ?>

                                <div class="text-center py-5">

                                    <i class="fas fa-code fa-4x text-secondary mb-3"></i>

                                    <h5>No Skills Added</h5>

                                    <p class="text-muted">

                                        Click "Add Skill" To Add Your Skills.

                                    </p>

                                </div>

                            <?php
                            }
                            ?>

                        </div>

                        <!-- </div> -->

                    </div>

                </div>

                <!-- Resume Card -->

                <div class="col-lg-5">

                    <div class="card shadow-sm">

                        <div class="card-header">

                            <h4 class="mb-0">

                                <i class="fas fa-file-pdf text-danger"></i>

                                Resume

                            </h4>

                        </div>

                        <div class="card-body p-4">
                            <?php

                            if (!empty($student['resume'])) {

                            ?>
                                <div id="resumeMessage"></div>

                                <div class="text-center">

                                    <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>

                                    <h5>

                                        <?php echo $student['resume']; ?>

                                    </h5>

                                    <a
                                        href="../assets/uploads/resumes/<?php echo $student['resume']; ?>"
                                        target="_blank"
                                        class="btn btn-success mt-3">

                                        <i class="fas fa-eye"></i>

                                        View Resume

                                    </a>

                                    <div class="mt-3">

                                        <button
                                            class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadResume">

                                            <i class="fas fa-sync-alt"></i>

                                            Replace Resume

                                        </button>

                                        <a
                                            href="?delete_resume=1"
                                            class="btn btn-danger">

                                            <i class="fas fa-trash"></i>

                                            Delete

                                        </a>

                                    </div>

                                </div>

                            <?php
                            } else {
                            ?>

                                <div class="text-center">

                                    <i class="fas fa-file-upload fa-5x text-secondary mb-3"></i>

                                    <h5>

                                        No Resume Uploaded

                                    </h5>
                                    <div class="mt-4">

                                        <button
                                            class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadResume">

                                            <i class="fas fa-upload"></i>

                                            Upload Resume

                                        </button>

                                    </div>

                                    <p class="text-muted">

                                        Upload Your Latest Resume

                                    </p>

                                </div>


                            <?php
                            }
                            ?>

                        </div>

                    </div>

                </div>

                <!-- Add Skill Modal -->

                <div
                    class="modal fade"
                    id="addSkill"
                    tabindex="-1">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form id="addSkillForm">

                                <div class="modal-header">

                                    <h5>

                                        Add Skill

                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">

                                    </button>

                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">

                                        <label>

                                            Skill Name

                                        </label>

                                        <select
                                            name="skill_name"
                                            class="form-select"
                                            required>

                                            <option value="">

                                                Select Skill

                                            </option>

                                            <?php

                                            $skillList = [

                                                "HTML",

                                                "CSS",

                                                "Bootstrap",

                                                "JavaScript",

                                                "jQuery",

                                                "AJAX",

                                                "PHP",

                                                "MySQL",

                                                "SQLite",

                                                "React",

                                                "Node.js",

                                                "Express.js",

                                                "MongoDB",

                                                "Java",

                                                "Python",

                                                "C",

                                                "C++",

                                                "C#",

                                                "ASP.NET",

                                                "VB.NET",

                                                "Git",

                                                "GitHub",

                                                "AWS",

                                                "Docker",

                                                "Linux"

                                            ];

                                            foreach ($skillList as $skill) {

                                            ?>

                                                <option
                                                    value="<?php echo $skill; ?>">

                                                    <?php echo $skill; ?>

                                                </option>

                                            <?php } ?>

                                        </select>

                                    </div>

                                    <div class="mb-3">

                                        <label>

                                            Skill Level

                                        </label>

                                        <select name="skill_level" class="form-select" required>

                                            <option value="">Select Level</option>

                                            <option value="Beginner">Beginner</option>

                                            <option value="Intermediate">Intermediate</option>

                                            <option value="Advanced">Advanced</option>

                                            <option value="Expert">Expert</option>

                                        </select>

                                    </div>

                                </div>

                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">

                                        Close

                                    </button>

                                    <button
                                        type="submit"
                                        name="add_skill"
                                        class="btn btn-primary">

                                        <i class="fas fa-plus"></i>

                                        Add Skill

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- Upload Resume Modal -->
                <div class="modal fade" id="uploadResume" tabindex="-1">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <form id="uploadResumeForm" enctype="multipart/form-data">

                                <div class="modal-header">

                                    <h5 class="modal-title">
                                        <i class="fas fa-file-upload"></i>
                                        Upload Resume
                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Select Resume (PDF Only)
                                        </label>

                                        <input
                                            type="file"
                                            name="resume"
                                            class="form-control"
                                            accept=".pdf"
                                            required>

                                        <small class="text-muted">
                                            Maximum File Size: 5 MB
                                        </small>

                                    </div>

                                </div>

                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">

                                        Cancel

                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-primary">

                                        <i class="fas fa-upload"></i>

                                        Upload Resume

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $("#addSkillForm").submit(function(e) {

            e.preventDefault();

            $.ajax({
                url: "ajax/add_skill.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",

                success: function(response) {

                    if (response.status == "success") {

                        $("#skillMessage").html(
                            '<div class="alert alert-success">' + response.message + '</div>'
                        );

                        // Modal Close
                        var modal = bootstrap.Modal.getInstance(document.getElementById('addSkill'));
                        modal.hide();

                        // Form Reset
                        $("#addSkillForm")[0].reset();

                        setTimeout(function() {
                            location.reload();
                        }, 1000);

                    } else {

                        $("#skillMessage").html(
                            '<div class="alert alert-danger">' + response.message + '</div>'
                        );

                        // Modal Close
                        var modal = bootstrap.Modal.getInstance(document.getElementById('addSkill'));
                        modal.hide();

                        // Form Reset
                        $("#addSkillForm")[0].reset();

                    }

                    setTimeout(function() {
                        $("#skillMessage").fadeOut(function() {
                            $(this).html("").show();
                        });
                    }, 3000);

                }

            });

        });
    </script>
    <script>
        $("#uploadResumeForm").submit(function(e) {

            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({

                url: "ajax/upload_resume.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",

                success: function(response) {

                    if (response.status == "success") {

                        $("#resumeMessage").html(
                            '<div class="alert alert-success">' + response.message + '</div>'
                        );

                        var modal = bootstrap.Modal.getInstance(document.getElementById('uploadResume'));

                        modal.hide();

                        $("#uploadResumeForm")[0].reset();

                        setTimeout(function() {
                            location.reload();
                        }, 1000);

                    } else {

                        $("#resumeMessage").html(
                            '<div class="alert alert-danger">' + response.message + '</div>'
                        );

                    }

                    setTimeout(function() {

                        $("#resumeMessage").fadeOut(function() {
                            $(this).html("").show();
                        });

                    }, 3000);

                }

            });

        });
    </script>
    <?php include('../includes/footer_student.php'); ?>
</div>