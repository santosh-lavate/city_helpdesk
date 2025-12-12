<?php
include('includes/db.php');

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // ✅ Hash the password before saving
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO providers (name, email, password, phone, service_type, address, verified, created_at)
              VALUES ('$name', '$email', '$hashed_password', '$phone', '$service_type', '$address', 0, NOW())";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful! You can now login.'); window.location='provider_login.php';</script>";
    } else {
        echo "<script>alert('Error while registering. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Provider Registration - City Services</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: linear-gradient(135deg, #00c6ff, #0072ff); height:100vh; display:flex; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:15px; width:380px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.2);">
        <h2>👷 Provider Registration</h2>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required 
                   style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            
            <input type="email" name="email" placeholder="Email Address" required 
                   style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            
            <input type="password" name="password" placeholder="Password" required 
                   style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            
            <input type="text" name="phone" placeholder="Phone Number" required 
                   style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            
            <input type="text" name="service_type" placeholder="Service Type (e.g. Plumber, Electrician)" required 
                   style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc;">
            
            <textarea name="address" placeholder="Address" required 
                      style="width:100%; padding:10px; margin:10px 0; border-radius:8px; border:1px solid #ccc; resize:none;"></textarea>
            
            <button type="submit" name="register" 
                    style="background:#007bff; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">
                Register
            </button>
        </form>

        <p style="margin-top:10px;">Already have an account? <a href="provider_login.php">Login here</a></p>
    </div>
</body>
</html>