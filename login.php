<?php
include('includes/db.php');
session_start();

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // encrypt password same as registration
    $hashed_password = md5($password);

    // Correct query using email
    $query = "SELECT * FROM users WHERE email='$email' AND password='$hashed_password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login - City Services</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, #0096c7, #023e8a);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 350px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        h2 {
            margin-bottom: 20px;
            color: #023e8a;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0096c7, #0077b6);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: linear-gradient(135deg, #0077b6, #023e8a);
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            text-decoration: none;
            color: #0077b6;
            font-weight: 500;
        }

        .error {
            color: red;
            font-weight: 500;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
  <div class="login-container">
    <h2>🔒 User Login</h2>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit" name="login">Login</button>
    </form>
    <p style="color:red;"><?php echo $error; ?></p>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>