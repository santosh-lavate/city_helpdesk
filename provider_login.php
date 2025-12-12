<?php
include('includes/db.php');
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM providers WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $provider = mysqli_fetch_assoc($result);

        // ✅ Check hashed password
        if (password_verify($password, $provider['password'])) {
            $_SESSION['provider_id'] = $provider['provider_id'];
            $_SESSION['provider_name'] = $provider['name'];
            header("Location: provider_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Provider Login - City Services</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: linear-gradient(135deg, #00c6ff, #0072ff); height:100vh; display:flex; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:15px; width:350px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);">
        <h2>👷 Provider Login</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            <input type="password" name="password" placeholder="Enter your password" required style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            <button type="submit" name="login" style="background:#007bff; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">Login</button>
        </form>

        <p style="margin-top:10px;">Don’t have an account? <a href="provider_register.php">Register here</a></p>
    </div>
</body>
</html>