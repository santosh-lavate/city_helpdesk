<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>City Services Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .home-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 400px;
        }
        .home-container h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .home-container p {
            color: #555;
            margin-bottom: 25px;
        }
        .btn {
            display: block;
            background: #0984e3;
            color: white;
            text-decoration: none;
            padding: 12px 0;
            margin: 10px 0;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn:hover {
            background: #74b9ff;
        }
    </style>
</head>
<body>

<div class="home-container">
    <h1>🏙️ City Services Portal</h1>
    <p>Welcome! Please choose your login type below:</p>

    <a href="login.php" class="btn">Login as User 👤</a>
    <a href="provider_login.php" class="btn">Login as Provider 🧑‍🔧</a>
    <a href="admin/index.php" class="btn">Login as Admin 🧑‍💼</a>

    <hr style="margin: 20px 0;">
    <p>Don’t have an account?</p>
    <a href="register.php" class="btn" style="background:#2ecc71;">Register as User ➕</a>
    <a href="provider_register.php" class="btn" style="background:#27ae60;">Register as Provider ➕</a>
</div>

</body>
</html>