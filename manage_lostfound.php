<?php
session_start();
require_once 'db_connect.php';

$loggedIn = isset($_SESSION['admin_id']); // check if admin is logged in
$collection = $db->lost_found;

$success_message = '';
$error_message = '';

// Handle admin update
if(isset($_POST['update_submit'])){
    if(!$loggedIn){
        $error_message = "You must be logged in as admin to update.";
    } else {
        try {
            $id = new MongoDB\BSON\ObjectId($_POST['id']);
            $status = $_POST['status'] ?? '';
            $note = trim($_POST['admin_note'] ?? '');

            if(empty($status)){
                throw new Exception("Please select a status.");
            }

            $collection->updateOne(
                ['_id' => $id],
                ['$set' => ['status' => $status, 'admin_note' => $note]]
            );

            $success_message = "Update successful!";
        } catch(Exception $e){
            $error_message = $e->getMessage();
        }
    }
}

// Fetch all submissions
$items = $collection->find([], ['sort' => ['_id' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Lost & Found</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
.container { max-width: 900px; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; }
h2 { text-align: center; margin-bottom: 20px; color: #2d3436; }
table { width: 100%; border-collapse: collapse; margin-top: 30px; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
th { background: #ff69b4; color: white; }
textarea, select { width: 100%; border-radius: 10px; padding: 8px; margin-top: 5px; }
button { background: #007BFF; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; margin-top: 5px; }
button:hover { background: #0056b3; }
.success-message { background: #00cec9; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
.error-message { background: #d63031; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
</style>
</head>
<body>
<div class="container">
    <h2>All Lost & Found Submissions</h2>

    <?php if($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if($error_message): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if(!$loggedIn): ?>
        <div class="error-message">Please <a href="admin_login.php" style="color:white;text-decoration:underline;">login</a> as admin to manage submissions.</div>
    <?php endif; ?>

    <table>
        <tr>
            <th>User</th>
            <th>Type</th>
            <th>Item</th>
            <th>Description</th>
            <th>Status</th>
            <th>Admin Note</th>
            <th>Action</th>
        </tr>

        <?php foreach($items as $i): ?>
        <tr>
            <td><?= htmlspecialchars($i['user_name'] ?? 'Unknown') ?></td>
            <td><?= $i['type'] ?></td>
            <td><?= htmlspecialchars($i['item_name']) ?></td>
            <td><?= htmlspecialchars($i['description']) ?></td>
            <td><?= $i['status'] ?></td>
            <td><?= $i['admin_note'] ?? '' ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $i['_id'] ?>">
                    <select name="status" <?php if(!$loggedIn) echo 'disabled'; ?>>
                        <option value="Pending" <?= $i['status']=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Resolved" <?= $i['status']=='Resolved'?'selected':'' ?>>Resolved</option>
                        <option value="Closed" <?= $i['status']=='Closed'?'selected':'' ?>>Closed</option>
                    </select>
                    <textarea name="admin_note" placeholder="Add note..." <?php if(!$loggedIn) echo 'disabled'; ?>><?= $i['admin_note'] ?? '' ?></textarea>
                    <button type="submit" name="update_submit" <?php if(!$loggedIn) echo 'disabled'; ?>>Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
