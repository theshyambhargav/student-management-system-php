<?php
session_start();

// Allow only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="page-center">

<div class="dashboard-container">

    <h2>Admin Dashboard</h2>

    <div class="profile-box">
        <p><strong>Welcome:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p>Student Management System Control Panel</p>
    </div>

    <div class="dashboard-actions">
        <a href="./subjects.php" class="btn">Manage Subjects</a>
        <a href="./teachers.php" class="btn">Manage Teachers</a>
        <a href="./assign_subjects.php" class="btn">Assign Subjects to Teachers</a>
        <a href="../auth/logout.php" class="btn logout">Logout</a>
    </div>

</div>

</body>
</html>