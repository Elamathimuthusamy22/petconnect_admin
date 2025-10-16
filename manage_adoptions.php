<?php
include 'db_connect.php';
$collection = $db->pets;

// Handle approval
if (isset($_GET['approve_id'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['approve_id']);
    $collection->updateOne(
        ['_id' => $id],
        ['$set' => ['status' => 'adopted']]
    );
    header("Location: manage_adoptions.php?success=1");
    exit();
}

// Handle rejection
if (isset($_GET['reject_id'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['reject_id']);
    $collection->updateOne(
        ['_id' => $id],
        ['$set' => ['status' => 'available']]
    );
    header("Location: manage_adoptions.php?rejected=1");
    exit();
}

// Get only pets that have adoption_status field (pending or adopted)
$pets = $collection->find([
    'status' => ['$exists' => true, '$in' => ['pending', 'adopted']]
]);
$petsArray = iterator_to_array($pets);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Adoptions | Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 40px; }
        h2 { text-align: center; margin-bottom: 30px; color: #2d3436; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #ff69b4; color: white; }
        tr:hover { background: #fff5f9; }
        .btn { padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; color: white; font-weight: bold; text-decoration: none; }
        .approve { background: #00b894; }
        .reject { background: #d63031; }
        .message { background: #55efc4; color: #00695c; text-align: center; padding: 10px; margin-bottom: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <h2>Manage Adoption Requests</h2>
    <?php if (isset($_GET['success'])): ?>
        <div class="message">Adoption request approved successfully ✅</div>
    <?php elseif (isset($_GET['rejected'])): ?>
        <div class="message" style="background:#fab1a0; color:#6d214f;">Adoption request rejected ❌</div>
    <?php endif; ?>

    <?php if (count($petsArray) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Type</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($petsArray as $pet): ?>
                    <tr>
                        <td><?= htmlspecialchars($pet['name']) ?></td>
                        <td><?= htmlspecialchars($pet['type']) ?></td>
                        <td><?= htmlspecialchars($pet['breed']) ?></td>
                        <td><?= htmlspecialchars($pet['age']) ?></td>
                        <td><?= ucfirst($pet['status']) ?></td>
                        <td>
                            <?php if($pet['status'] == 'pending'): ?>
                                <a href="?approve_id=<?= $pet['_id'] ?>" class="btn approve">Approve</a>
                                <a href="?reject_id=<?= $pet['_id'] ?>" class="btn reject">Reject</a>
                            <?php elseif($pet['status'] == 'adopted'): ?>
                                <span style="color:green;font-weight:bold;">Adopted</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:#636e72; margin-top:50px;">No pending adoption requests 🐾</p>
    <?php endif; ?>
</body>
</html>
