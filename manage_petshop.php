<?php
session_start();
include 'db_connect.php';
$collection = $db->petshop;

// Handle Add Item
if(isset($_POST['add_item'])){
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    // Handle image upload
    $imageData = null;
    $imageType = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];
    }

    $collection->insertOne([
        'name' => $name,
        'type' => $type,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $imageData ? new MongoDB\BSON\Binary($imageData, MongoDB\BSON\Binary::TYPE_GENERIC) : null,
        'image_type' => $imageType ?? null,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    $successMessage = "Item added successfully!";
}

// Fetch all items
$items = $collection->find();
$itemsArray = iterator_to_array($items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Pet Shop | Admin</title>
<style>
body{font-family:Arial;background:#f8f9fa;padding:40px;}
h2{text-align:center;color:#2d3436;}
form, .items-grid{background:#fff;padding:20px;border-radius:20px;box-shadow:0 5px 20px rgba(0,0,0,0.1);margin-bottom:30px;}
form input, form select{width:100%;padding:10px;margin:10px 0;border-radius:10px;border:1px solid #ccc;}
form button{padding:10px 20px;border:none;border-radius:10px;background:#ff69b4;color:white;cursor:pointer;}
.items-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;}
.item-card{background:#fff;border-radius:15px;padding:15px;box-shadow:0 3px 15px rgba(0,0,0,0.1);}
.item-card img{width:100%;height:150px;object-fit:cover;border-radius:10px;}
.item-info{margin-top:10px;}
.item-name{font-weight:bold;color:#2d3436;font-size:18px;}
.item-type, .item-price, .item-qty{font-size:14px;color:#636e72;margin-top:3px;}
.message{padding:10px;margin-bottom:20px;border-radius:10px;color:#fff;background:#00b894;}
</style>
</head>
<body>

<h2>Manage Pet Shop Items</h2>

<?php if(isset($successMessage)): ?>
    <div class="message"><?php echo $successMessage; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Item Name" required>
    <input type="text" name="type" placeholder="Type (Food / Toy / Accessory)" required>
    <input type="number" name="price" step="0.01" placeholder="Price" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="file" name="image" accept="image/*">
    <button type="submit" name="add_item">Add Item</button>
</form>

<div class="items-grid">
    <?php foreach($itemsArray as $item): ?>
        <div class="item-card">
            <?php 
            if(isset($item['image']) && $item['image'] instanceof MongoDB\BSON\Binary){
                $imgType = $item['image_type'] ?? 'image/jpeg';
                $imgData = base64_encode($item['image']->getData());
                echo "<img src='data:$imgType;base64,$imgData'>";
            } else {
                echo "<div style='height:150px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;'>No Image</div>";
            }
            ?>
            <div class="item-info">
                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="item-type">Type: <?php echo htmlspecialchars($item['type']); ?></div>
                <div class="item-price">Price: $<?php echo $item['price']; ?></div>
                <div class="item-qty">Qty: <?php echo $item['quantity']; ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
