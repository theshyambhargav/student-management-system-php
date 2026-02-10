<?php
session_start();
include "../connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$edit = false;

/* DELETE */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM subjects WHERE id=$id");
    header("Location: subjects.php");
    exit();
}

/* EDIT LOAD */
if (isset($_GET['edit'])) {
    $edit = true;
    $id = intval($_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM subjects WHERE id=$id");
    $data = mysqli_fetch_assoc($res);
}

/* ADD / UPDATE */
if (isset($_POST['save'])) {
    $id   = $_POST['id'];
    $name = $_POST['subject_name'];
    $course = $_POST['course'];
    $sem = $_POST['semester'];
    $syllabus = $_POST['syllabus'];

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO subjects (subject_name, course, semester, syllabus)
                             VALUES ('$name','$course','$sem','$syllabus')");
    } else {
        $id = intval($id);
        mysqli_query($conn, "UPDATE subjects SET
            subject_name='$name',
            course='$course',
            semester='$sem',
            syllabus='$syllabus'
            WHERE id=$id");
    }

    header("Location: subjects.php");
    exit();
}

$subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<div class="form-container" style="max-width:1000px">
    <h2>Manage Subjects</h2>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $edit ? $data['id'] : '' ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="subject_name" value="<?= $edit ? $data['subject_name'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Course</label>
                <input type="text" name="course" value="<?= $edit ? $data['course'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" min="1" max="10" value="<?= $edit ? $data['semester'] : '' ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Syllabus</label>
            <textarea name="syllabus" rows="3"><?= $edit ? $data['syllabus'] : '' ?></textarea>
        </div>

        <button type="submit" name="save"><?= $edit ? 'Update Subject' : 'Add Subject' ?></button>
    </form>

    <hr style="margin:30px 0">

    <h3>All Subjects</h3>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($subjects)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['subject_name'] ?></td>
            <td><?= $row['course'] ?></td>
            <td><?= $row['semester'] ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete subject?')" class="danger-link">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="back-link">← Back</a>
</div>
</body>
</html>