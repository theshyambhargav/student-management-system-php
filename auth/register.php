<?php
include "../connection.php";

$error = "";

if (isset($_POST['register'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $course   = trim($_POST['course']);
    $semester = (int) $_POST['semester'];

    if ($name == "" || $email == "" || $password == "" || $course == "" || $semester == "") {
        $error = "All fields are required";
    } elseif ($semester < 1 || $semester > 10) {
        $error = "Semester must be between 1 and 10";
    } else {

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already registered";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertUser = "INSERT INTO users (name, email, password, role)
                           VALUES ('$name', '$email', '$hashedPassword', 'student')";

            if (mysqli_query($conn, $insertUser)) {

                $user_id = mysqli_insert_id($conn);

                $insertStudent = "INSERT INTO students (user_id, course, semester)
                                  VALUES ('$user_id', '$course', '$semester')";

                if (mysqli_query($conn, $insertStudent)) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Student profile creation failed.";
                }

            } else {
                $error = "User registration failed.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<form class="form-container" method="POST">
    <h2>Student Registration</h2>

    <?php if ($error != ""): ?>
        <div class="alert-box"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Course</label>
            <input type="text" name="course" required>
        </div>

        <div class="form-group">
            <label>Semester</label>
            <input type="number" name="semester" min="1" max="10" required>
        </div>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit" name="register">Register</button>
</form>

</body>
</html>
