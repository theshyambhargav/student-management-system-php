<?php
$allowed_roles = ['teacher'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

$teacher_id = $_SESSION['user_id'];

$subjects = mysqli_query($conn, "
    SELECT subjects.id, subjects.subject_name, subjects.course, subjects.semester
    FROM subjects
    JOIN teacher_subjects ON subjects.id = teacher_subjects.subject_id
    WHERE teacher_subjects.teacher_id = '$teacher_id'
");
?>

<div class="page-center">
<div class="form-container" style="max-width:950px">

    <h2>Teacher Dashboard</h2>

    <div class="profile-box">
        <p><strong>Welcome:</strong> <?php echo $_SESSION['name']; ?></p>
        <p>Your Assigned Subjects</p>
    </div>

    <table width="100%" cellpadding="10" style="border-collapse:collapse;">
        <tr style="background:#f1f5f9">
            <th>Subject</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Actions</th>
        </tr>

        <?php while ($sub = mysqli_fetch_assoc($subjects)): ?>
        <tr>
            <td><?php echo $sub['subject_name']; ?></td>
            <td><?php echo $sub['course']; ?></td>
            <td><?php echo $sub['semester']; ?></td>
            <td>
                <a class="btn" href="syllabus.php?id=<?php echo $sub['id']; ?>">Syllabus</a>
                <a class="btn secondary" href="marks.php?subject_id=<?php echo $sub['id']; ?>">Marks</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>
</div>

<?php include "../includes/footer.php"; ?>