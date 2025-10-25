<?php
session_start();
include 'db_connect.php';

$collection = $db->feedback;

$success_message = '';
$error_message = '';

// Handle admin reply
if(isset($_POST['reply_submit'])){
    try {
        $id = new MongoDB\BSON\ObjectId($_POST['id']);
        $reply = trim($_POST['reply']);

        if(empty($reply)){
            throw new Exception("Please enter a reply message.");
        }

        $collection->updateOne(
            ['_id' => $id],
            ['$set' => ['admin_reply' => $reply]]
        );

        $success_message = "Reply sent successfully!";
    } catch(Exception $e){
        $error_message = $e->getMessage();
    }
}

// Fetch all feedbacks
$feedbacks = $collection->find([], ['sort' => ['_id' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Feedbacks | Admin</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
.container { background: #fff; padding: 25px; border-radius: 15px; width: 900px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2d3436; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
th { background: #ff69b4; color: white; }
textarea { width: 100%; height: 60px; border-radius: 8px; border: 1px solid #ccc; padding: 5px; resize: none; }
button { background: #007BFF; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; margin-top: 5px; }
button:hover { background: #0056b3; }
.success-message { background: #00cec9; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
.error-message { background: #d63031; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
</style>
</head>
<body>
<div class="container">
<h2>All User Feedbacks</h2>

<?php if($success_message): ?>
    <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<?php if($error_message): ?>
    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<table>
<tr><th>User Name</th><th>Message</th><th>Admin Reply</th><th>Action</th></tr>
<?php foreach($feedbacks as $f): ?>
<tr>
    <td><?= htmlspecialchars($f['user_name'] ?? 'Unknown User') ?></td>
    <td><?= htmlspecialchars($f['message']) ?></td>
    <td><?= isset($f['admin_reply']) && $f['admin_reply'] ? htmlspecialchars($f['admin_reply']) : 'No reply yet' ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="id" value="<?= $f['_id'] ?>">
            <textarea name="reply" placeholder="Type reply..."><?= $f['admin_reply'] ?? '' ?></textarea>
            <button type="submit" name="reply_submit">Send Reply</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
