<?php
session_start();
include "../connection.php";

/* 🔒 ACCESS CONTROL */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

/* ➕ ASSIGN SUBJECT */
if (isset($_POST['assign'])) {
    $teacher_id = intval($_POST['teacher_id']);
    $subject_id = intval($_POST['subject_id']);

    $check = mysqli_query($conn, "SELECT id FROM teacher_subjects 
                                  WHERE teacher_id=$teacher_id AND subject_id=$subject_id");

    if (mysqli_num_rows($check) > 0) {
        $message = "Teacher already assigned to this subject.";
    } else {
        mysqli_query($conn, "INSERT INTO teacher_subjects (teacher_id, subject_id) 
                             VALUES ($teacher_id, $subject_id)");
        $message = "Assignment successful.";
    }
}

/* ❌ REMOVE ASSIGNMENT */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM teacher_subjects WHERE id=$id");
    header("Location: assign_subjects.php");
    exit();
}

/* 📥 FETCH TEACHERS */
$teachers = mysqli_query($conn, "SELECT id, name FROM users WHERE role='teacher' ORDER BY name");

/* 📥 FETCH SUBJECTS */
$subjects = mysqli_query($conn, "SELECT id, subject_name, semester FROM subjects ORDER BY subject_name");

/* 📥 FETCH ASSIGNMENTS */
$assignments = mysqli_query($conn, "
    SELECT ts.id, u.name AS teacher_name,
           s.subject_name, s.course, s.semester
    FROM teacher_subjects ts
    JOIN users u ON u.id = ts.teacher_id
    JOIN subjects s ON s.id = ts.subject_id
    ORDER BY u.name
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Subjects</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<div class="form-container" style="max-width:1000px">

    <h2>Assign Teacher to Subject</h2>

    <?php if ($message != ""): ?>
        <div class="alert-box"><?= $message ?></div>
    <?php endif; ?>

    <!-- 🟦 ASSIGN FORM -->
    <form method="POST" style="margin-bottom:30px;">
        <div class="form-row">
            <div class="form-group">
                <label>Select Teacher</label>
                <select name="teacher_id" required>
                    <option value="">-- Select Teacher --</option>
                    <?php while ($t = mysqli_fetch_assoc($teachers)): ?>
                        <option value="<?= $t['id']; ?>"><?= $t['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Subject</label>
                <select name="subject_id" required>
                    <option value="">-- Select Subject --</option>
                    <?php while ($s = mysqli_fetch_assoc($subjects)): ?>
                        <option value="<?= $s['id']; ?>">
                            <?= $s['subject_name'] . " (Sem " . $s['semester'] . ")" ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <button type="submit" name="assign">Assign Subject</button>
    </form>

    <hr style="margin:30px 0">

    <!-- 🟦 ASSIGNMENT LIST -->
    <h3>Current Assignments</h3>

    <table class="data-table">
        <tr>
            <th>Teacher</th>
            <th>Subject</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Action</th>
        </tr>

        <?php while ($a = mysqli_fetch_assoc($assignments)): ?>
        <tr>
            <td><?= $a['teacher_name']; ?></td>
            <td><?= $a['subject_name']; ?></td>
            <td><?= $a['course']; ?></td>
            <td><?= $a['semester']; ?></td>
            <td>
                <a href="?delete=<?= $a['id']; ?>" class="danger-link" onclick="return confirm('Remove assignment?')">Remove</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php" class="back-link">← Back</a>

</div>
</body>
</html>