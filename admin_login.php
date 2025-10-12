<?php
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Static credentials
    $adminEmail = "admin";
    $adminPassword = "admin";

    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION['admin_email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | PetConnect</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 400px;
            margin: 100px auto;
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #1e90ff;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background: #1e90ff;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0a66c2;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>🐶 PetConnect Admin Login</h2>

    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="email" placeholder="Enter Email" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
