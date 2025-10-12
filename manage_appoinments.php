<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$appointments = $db->appointments->find();

if (isset($_GET['approve'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['approve']);
    $db->appointments->updateOne(['_id' => $id], ['$set' => ['status' => 'approved']]);
    header("Location: manage_appointments.php");
}
if (isset($_GET['reject'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['reject']);
    $db->appointments->updateOne(['_id' => $id], ['$set' => ['status' => 'rejected']]);
    header("Location: manage_appointments.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Appointments</title>
  <style>
    body { font-family: Arial; background: #f3f3f3; }
    table { width: 90%; margin: 20px auto; border-collapse: collapse; }
    th, td { border: 1px solid gray; padding: 10px; text-align: center; }
    th { background: #2b7a78; color: white; }
    a { text-decoration: none; padding: 5px 10px; color: white; border-radius: 5px; }
    .approve { background: green; }
    .reject { background: red; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Manage Appointments</h2>
  <table>
    <tr><th>User</th><th>Date</th><th>Time</th><th>Reason</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($appointments as $app): ?>
      <tr>
        <td><?= $app['userId'] ?></td>
        <td><?= $app['date'] ?></td>
        <td><?= $app['time'] ?></td>
        <td><?= $app['reason'] ?></td>
        <td><?= $app['status'] ?></td>
        <td>
          <a href="?approve=<?= $app['_id'] ?>" class="approve">Approve</a>
          <a href="?reject=<?= $app['_id'] ?>" class="reject">Reject</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
