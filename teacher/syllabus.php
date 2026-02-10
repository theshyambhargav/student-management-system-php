<?php
$allowed_roles = ['teacher'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

$id = intval($_GET['id']);

$subject = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT subject_name, course, semester, syllabus FROM subjects WHERE id='$id'"
));
?>

<div class="page-center">
<div class="form-container" style="max-width:800px">

    <h2>Syllabus</h2>

    <div class="profile-box">
        <p><strong>Subject:</strong> <?php echo $subject['subject_name']; ?></p>
        <p><strong>Course:</strong> <?php echo $subject['course']; ?></p>
        <p><strong>Semester:</strong> <?php echo $subject['semester']; ?></p>
    </div>

    <p style="margin:15px 0;"><?php echo nl2br($subject['syllabus']); ?></p>

    <a href="dashboard.php" class="btn">Back</a>

</div>
</div>

<?php include "../includes/footer.php"; ?>