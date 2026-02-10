<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Student Management System</h2>

    <div class="profile-box">
        <p><strong>Welcome:</strong> <?php echo $_SESSION['name']; ?></p>
        <p><strong>Role:</strong> <?php echo ucfirst($_SESSION['role']); ?></p>
    </div>

    <div class="dashboard-actions">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a class="btn" href="dashboard.php">Dashboard</a>
            <a class="btn" href="subjects.php">Subjects</a>
            <a class="btn" href="teachers.php">Teachers</a>
            <a class="btn" href="students.php">Students</a>
            <a class="btn" href="assign_teacher.php">Assign Teacher</a>
        <?php elseif ($_SESSION['role'] == 'teacher'): ?>
            <a class="btn" href="dashboard.php">Dashboard</a>
            <a class="btn" href="marks.php">Manage Marks</a>
        <?php elseif ($_SESSION['role'] == 'student'): ?>
            <a class="btn" href="dashboard.php">Dashboard</a>
            <a class="btn" href="marks.php">My Marks</a>
        <?php endif; ?>

        <a class="btn logout" href="../auth/logout.php">Logout</a>
    </div>

    <hr>