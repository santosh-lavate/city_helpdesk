<?php
include('../includes/db.php');
 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Count totals
$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$providers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM providers"))['total'];
$bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Welcome, Admin 👋</h2>
    <a href="logout.php">Logout</a>

    <hr>
    <h3>📊 Summary</h3>
    <p>Total Users: <?php echo $users; ?></p>
    <p>Total Providers: <?php echo $providers; ?></p>
    <p>Total Bookings: <?php echo $bookings; ?></p>

    <hr>
    <a href="manage_users.php">Manage Users</a> | 
    <a href="manage_providers.php">Manage Providers</a> | 
    <a href="reports.php">View Reports</a>
</body>
</html>