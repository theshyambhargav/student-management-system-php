<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* User must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

/* Optional role restriction
   Usage: $allowed_roles = ['admin']; include this file;
*/
if (isset($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
    echo "Access Denied.";
    exit();
}
?>