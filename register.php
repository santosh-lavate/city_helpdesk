<?php
include('includes/db.php');
session_start();

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Encrypt password
    $hashed_password = md5($password);

    $query = "INSERT INTO users (name, email, password, phone, address) 
              VALUES ('$name', '$email', '$hashed_password', '$phone', '$address')";

  if (mysqli_query($conn, $query)) {
    $success = "✅ Registration successful! You can now login.";
} else {
    $error = "❌ Database Error: " . mysqli_error($conn);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration - City Services</title>
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

        .register-container {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 380px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        h2 {
            margin-bottom: 20px;
            color: #023e8a;
        }

        input, textarea {
            width: 90%;
            padding: 12px;
            margin: 8px 0;
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

        .success {
            color: green;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            font-weight: 500;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>📝 User Registration</h2>

        <?php 
        if (isset($success)) echo "<p class='success'>$success</p>"; 
        if (isset($error)) echo "<p class='error'>$error</p>"; 
        ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="address" placeholder="Address" required></textarea>
            <button type="submit" name="register">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>