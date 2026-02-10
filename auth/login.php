<?php
session_start();
include "../connection.php";

$error = "";

if (isset($_POST['login_btn'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        $error = "All fields are required";
    } else {

        $sql = "SELECT id, name, email, password, role FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name']    = $user['name'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['role']    = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'student') {
                    header("Location: ../student/dashboard.php");
                } elseif ($user['role'] == 'teacher') {
                    header("Location: ../teacher/dashboard.php");
                } elseif ($user['role'] == 'admin') {
                    header("Location: ../admin/dashboard.php");
                }

                exit();
            }
        }

        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<form class="form-container" method="POST">
    <h2>Login</h2>

    <?php if ($error != ""): ?>
        <div class="alert-box"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit" name="login_btn">Login</button>
</form>

</body>
</html>