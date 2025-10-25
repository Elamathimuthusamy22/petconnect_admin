<?php
session_start();

// Unset all admin session variables
unset($_SESSION['admin_email']);

// Destroy the session
session_destroy();

// Redirect to admin login page
header("Location: admin_login.php");
exit();
?>
