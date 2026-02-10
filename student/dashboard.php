<?php
$allowed_roles = ['student'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

$user_id = $_SESSION['user_id'];

/* Get student course & semester */
$student = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT course, semester FROM students WHERE user_id='$user_id'"
));

$course = $student['course'];
$semester = $student['semester'];

/* Fetch subjects */
$subjects = mysqli_query($conn,
    "SELECT id, subject_name FROM subjects 
     WHERE course='$course' AND semester='$semester'"
);
?>

<h3>Student Dashboard</h3>

<div class="profile-box">
    <p><strong>Course:</strong> <?php echo $course; ?></p>
    <p><strong>Semester:</strong> <?php echo $semester; ?></p>
</div>

<h3>My Subjects</h3>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Subject</th>
        <th>Syllabus</th>
    </tr>

    <?php while ($sub = mysqli_fetch_assoc($subjects)): ?>
    <tr>
        <td><?php echo $sub['subject_name']; ?></td>
        <td>
            <a href="syllabus.php?id=<?php echo $sub['id']; ?>" class="btn">View</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include "../includes/footer.php"; ?>