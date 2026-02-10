<?php
$allowed_roles = ['teacher'];
include "../includes/auth_check.php";
include "../includes/header.php";
include "../connection.php";

$teacher_id = $_SESSION['user_id'];

if (!isset($_GET['subject_id']) || !is_numeric($_GET['subject_id'])) {
    echo "<div class='page-center'><div class='form-container'>
            <div class='alert-box'>Invalid subject access.</div>
            <a href='dashboard.php' class='btn'>Back</a>
          </div></div>";
    include "../includes/footer.php";
    exit();
}

$subject_id = intval($_GET['subject_id']);

$subject = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT course, semester, subject_name FROM subjects WHERE id='$subject_id'"
));

if (!$subject) {
    echo "<div class='page-center'><div class='form-container'>
            <div class='alert-box'>Subject not found.</div>
            <a href='dashboard.php' class='btn'>Back</a>
          </div></div>";
    include "../includes/footer.php";
    exit();
}

if (isset($_POST['save'])) {
    foreach ($_POST['marks'] as $student_id => $mark) {
        mysqli_query($conn, "INSERT INTO marks (student_id, subject_id, teacher_id, marks)
            VALUES ('$student_id','$subject_id','$teacher_id','$mark')
            ON DUPLICATE KEY UPDATE marks='$mark'");
    }
}

$students = mysqli_query($conn, "
    SELECT users.id, users.name
    FROM users
    JOIN students ON users.id = students.user_id
    WHERE users.role='student'
    AND students.course='{$subject['course']}'
    AND students.semester='{$subject['semester']}'
");
?>

<div class="page-center">
<div class="form-container" style="max-width:950px">

<h2>Enter Marks — <?php echo $subject['subject_name']; ?></h2>

<form method="POST">
<table width="100%" cellpadding="10" style="border-collapse:collapse;">
<tr style="background:#f1f5f9">
    <th>Student</th>
    <th>Marks</th>
</tr>

<?php while ($stu = mysqli_fetch_assoc($students)): ?>
<tr>
    <td><?php echo $stu['name']; ?></td>
    <td><input type="number" name="marks[<?php echo $stu['id']; ?>]" min="0" max="100"></td>
</tr>
<?php endwhile; ?>
</table>

<button class="btn" type="submit" name="save">Save Marks</button>
<a href="dashboard.php" class="btn secondary">Back</a>
</form>

</div>
</div>

<?php include "../includes/footer.php"; ?>