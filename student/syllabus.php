<?php
$allowed_roles = ['student'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

if (!isset($_GET['id'])) {
    echo "Invalid subject.";
    include "../includes/footer.php";
    exit();
}

$id = $_GET['id'];

/* Fetch subject details */
$subject = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT subject_name, course, semester, syllabus 
     FROM subjects WHERE id='$id'"
));
?>

<h3>Syllabus Details</h3>

<div class="profile-box">
    <p><strong>Subject:</strong> <?php echo $subject['subject_name']; ?></p>
    <p><strong>Course:</strong> <?php echo $subject['course']; ?></p>
    <p><strong>Semester:</strong> <?php echo $subject['semester']; ?></p>
</div>

<h4>Syllabus</h4>
<p><?php echo nl2br($subject['syllabus']); ?></p>

<a href="dashboard.php" class="btn">Back</a>

<?php include "../includes/footer.php"; ?>