<?php
session_start();
include "../connection.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* DELETE */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM teachers WHERE user_id=$id");
    mysqli_query($conn, "DELETE FROM users WHERE id=$id AND role='teacher'");
    header("Location: teachers.php");
    exit();
}

/* ADD */
if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $qual  = $_POST['qualification'];
    $exp   = $_POST['experience'];
    $spec  = $_POST['specialization'];

    mysqli_query($conn, "INSERT INTO users (name,email,password,role)
                         VALUES ('$name','$email','$pass','teacher')");

    $user_id = mysqli_insert_id($conn);

    mysqli_query($conn, "INSERT INTO teachers (user_id,qualification,experience,specialization)
                         VALUES ($user_id,'$qual','$exp','$spec')");

    header("Location: teachers.php");
    exit();
}

$teachers = mysqli_query($conn, "
    SELECT users.id, users.name, users.email,
           teachers.qualification, teachers.experience, teachers.specialization
    FROM users
    JOIN teachers ON users.id = teachers.user_id
    WHERE users.role='teacher'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Teachers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<div class="form-container" style="max-width:1000px">
    <h2>Manage Teachers</h2>

    <form method="POST" style="margin-bottom:30px;">
        <div class="form-row">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Qualification</label>
                <input type="text" name="qualification">
            </div>
            <div class="form-group">
                <label>Experience (years)</label>
                <input type="number" name="experience">
            </div>
            <div class="form-group">
                <label>Specialization</label>
                <input type="text" name="specialization">
            </div>
        </div>

        <button type="submit" name="add">Add Teacher</button>
    </form>

    <h3>Teacher List</h3>

    <table class="data-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Qualification</th>
            <th>Experience</th>
            <th>Specialization</th>
            <th>Action</th>
        </tr>

        <?php while($t = mysqli_fetch_assoc($teachers)) { ?>
        <tr>
            <td><?= $t['name'] ?></td>
            <td><?= $t['email'] ?></td>
            <td><?= $t['qualification'] ?></td>
            <td><?= $t['experience'] ?> yrs</td>
            <td><?= $t['specialization'] ?></td>
            <td>
                <a href="?delete=<?= $t['id'] ?>" class="danger-link" onclick="return confirm('Delete teacher?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php" class="back-link">← Back</a>
</div>
</body>
</html>