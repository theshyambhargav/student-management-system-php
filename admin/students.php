<?php
session_start();
include "../connection.php";

if ($_SESSION['role'] !== 'admin') header("Location: ../auth/login.php");

/* DELETE */
if (isset($_GET['delete'])) {
    mysqli_query($conn, "DELETE FROM users WHERE id=".$_GET['delete']." AND role='student'");
    header("Location: students.php");
}

$students = mysqli_query($conn, "
    SELECT users.id, users.name, users.email, students.course, students.semester
    FROM users
    JOIN students ON users.id = students.user_id
    WHERE users.role='student'
");
?>