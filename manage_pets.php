<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$pets = $db->pets->find();

if (isset($_GET['delete'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['delete']);
    $db->pets->deleteOne(['_id' => $id]);
    header("Location: manage_pets.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Pets</title>
  <style>
    body { font-family: Arial; background: #f3f3f3; }
    table { width: 90%; margin: 20px auto; border-collapse: collapse; }
    th, td { border: 1px solid gray; padding: 10px; text-align: center; }
    th { background: #2b7a78; color: white; }
    img { width: 80px; height: 80px; border-radius: 10px; }
    a { color: red; text-decoration: none; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Manage Pets</h2>
  <table>
    <tr><th>Image</th><th>Name</th><th>Breed</th><th>Age</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($pets as $pet): ?>
      <tr>
        <td><img src="../uploads/<?= $pet['image'] ?>"></td>
        <td><?= $pet['name'] ?></td>
        <td><?= $pet['breed'] ?></td>
        <td><?= $pet['age'] ?></td>
        <td><?= $pet['status'] ?></td>
        <td><a href="?delete=<?= $pet['_id'] ?>" onclick="return confirm('Delete this pet?')">Delete</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
