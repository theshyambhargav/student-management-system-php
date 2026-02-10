<?php
$allowed_roles = ['student'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

$student_id = $_SESSION['user_id'];

/* Fetch marks with subject details */
$marks = mysqli_query($conn, "
    SELECT subjects.subject_name, subjects.course, subjects.semester, marks.marks
    FROM marks
    JOIN subjects ON subjects.id = marks.subject_id
    WHERE marks.student_id = '$student_id'
");
?>

<h3>My Marks</h3>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
<tr>
    <th>Subject</th>
    <th>Course</th>
    <th>Semester</th>
    <th>Marks</th>
</tr>

<?php if(mysqli_num_rows($marks) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($marks)): ?>
        <tr>
            <td><?php echo $row['subject_name']; ?></td>
            <td><?php echo $row['course']; ?></td>
            <td><?php echo $row['semester']; ?></td>
            <td><strong><?php echo $row['marks']; ?></strong></td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="4" align="center">No marks available yet</td>
</tr>
<?php endif; ?>

</table>

<a href="dashboard.php" class="btn">Back to Dashboard</a>

<?php include "../includes/footer.php"; ?>