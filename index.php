<?php
session_start();
include 'db_connect.php';

// Optional: check if admin is logged in
// if(!isset($_SESSION['admin_email'])){
//     header("Location: admin_login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PetConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="admin-dashboard">
    <h1>Welcome, Admin 👋</h1>
    <p>Manage all PetConnect sections from here.</p>

    <div class="dashboard-grid">
        <a href="add_pet.php" class="dash-card">🐾 Add  Pets</a>
        <a href="manage_adoptions.php" class="dash-card">🏠 Adoption Requests</a>
        <a href="manage_appoinments.php" class="dash-card">🩺 Appointments</a>
        <a href="manage_petshop.php" class="dash-card">🛍️ Pet Shop</a>
        <a href="feedback.php" class="dash-card">💬 User Feedback</a>
       
        <a href="manage_lostfound.php" class="dash-card">🔍 Lost & Found</a>
        <a href="manage_users.php" class="dash-card">👥 Manage Users</a>
    </div>

    <a href="logout.php" class="logout-btn">🚪 Logout</a>
</div>

</body>
</html>
