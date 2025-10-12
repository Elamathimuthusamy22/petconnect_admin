<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit(); }

$feedbacks = $db->feedback->find();

if (isset($_POST['reply'])) {
    $id = new MongoDB\BSON\ObjectId($_POST['id']);
    $reply = $_POST['reply_text'];
    $db->feedback->updateOne(['_id' => $id], ['$set' => ['reply' => $reply]]);
    header("Location: feedback.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Feedback</title>
  <style>
    body { font-family: Arial; background: #f3f3f3; }
    .box {
      width: 80%; margin: 20px auto; background: white; padding: 20px;
      border-radius: 8px; box-shadow: 0 0 10px gray;
    }
    textarea { width: 100%; margin-top: 8px; padding: 10px; }
    button { padding: 8px 15px; background: #2b7a78; color: white; border: none; border-radius: 5px; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">User Feedback</h2>
  <?php foreach ($feedbacks as $fb): ?>
    <div class="box">
      <p><strong>User:</strong> <?= $fb['userId'] ?></p>
      <p><strong>Feedback:</strong> <?= $fb['message'] ?></p>
      <p><strong>Reply:</strong> <?= $fb['reply'] ?? 'No reply yet' ?></p>
      <form method="POST">
        <input type="hidden" name="id" value="<?= $fb['_id'] ?>">
        <textarea name="reply_text" placeholder="Write a reply..." required></textarea>
        <button name="reply">Send Reply</button>
      </form>
    </div>
  <?php endforeach; ?>
</body>
</html>
