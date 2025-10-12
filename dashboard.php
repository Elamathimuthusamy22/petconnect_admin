<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$pets = $db->pets->countDocuments();
$users = $db->users->countDocuments(['role' => 'user']);
$adoptions = $db->adoption_requests->countDocuments();
$appointments = $db->appointments->countDocuments();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <style>
    body { font-family: Arial; background: #f3f3f3; }
    .container { width: 80%; margin: 30px auto; }
    .card {
      display: inline-block; width: 22%; margin: 1%;
      background: white; padding: 20px; border-radius: 8px;
      box-shadow: 0 0 10px gray; text-align: center;
    }
    a { text-decoration: none; color: #2b7a78; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, Admin</h2>
    <a href="add_pet.php">Add Pet</a> | 
    <a href="manage_pets.php">Manage Pets</a> | 
    <a href="manage_appointments.php">Appointments</a> | 
    <a href="feedback.php">Feedback</a> | 
    <a href="logout.php">Logout</a>
    <hr>
    <div class="card"><h3>Total Pets</h3><p><?= $pets ?></p></div>
    <div class="card"><h3>Total Users</h3><p><?= $users ?></p></div>
    <div class="card"><h3>Adoptions</h3><p><?= $adoptions ?></p></div>
    <div class="card"><h3>Appointments</h3><p><?= $appointments ?></p></div>
  </div>
</body>
</html>
