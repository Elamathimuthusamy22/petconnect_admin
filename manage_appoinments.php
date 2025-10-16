<?php
require_once 'db_connect.php';
$collection = $db->appointments;

// Handle approval (when admin clicks "Accept")
if (isset($_GET['accept_id'])) {
    $acceptId = new MongoDB\BSON\ObjectId($_GET['accept_id']);
    $updateResult = $collection->updateOne(
        ['_id' => $acceptId],
        ['$set' => ['status' => 'accepted']]
    );
    if ($updateResult->getModifiedCount() > 0) {
        $message = "Appointment accepted successfully!";
    }
}

// Fetch all appointments (sorted by creation date)
$appointments = $collection->find([], ['sort' => ['created_at' => -1]]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2d3436;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #ff69b4;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }

        tr:hover {
            background-color: #fff5f9;
        }

        .status {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 13px;
            text-transform: capitalize;
        }

        .pending {
            background: #ffeaa7;
            color: #d35400;
        }

        .accepted {
            background: #55efc4;
            color: #00695c;
        }

        .btn-accept {
            background: #00cec9;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-accept:hover {
            background: #00b894;
        }

        .message {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            table {
                font-size: 13px;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Manage Appointments</h2>

    <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Pet Name</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['name']) ?></td>
                    <td><?= htmlspecialchars($appointment['pet_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['service']) ?></td>
                    <td><?= htmlspecialchars($appointment['date']) ?></td>
                    <td><?= htmlspecialchars($appointment['time']) ?></td>
                    <td><?= htmlspecialchars($appointment['notes'] ?? '-') ?></td>
                    <td>
                        <span class="status <?= htmlspecialchars($appointment['status']) ?>">
                            <?= htmlspecialchars($appointment['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($appointment['status'] === 'pending'): ?>
                            <a href="?accept_id=<?= $appointment['_id'] ?>">
                                <button class="btn-accept">Accept</button>
                            </a>
                        <?php else: ?>
                            ✅
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
