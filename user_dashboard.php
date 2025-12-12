<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - City Services</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-card">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?> 👋</h2>
        <p>What would you like to do today?</p>

        <div class="btn-group">
            <a href="service_list.php" class="btn primary">⚡ Book a Service</a>
            <a href="user_tracking.php" class="btn secondary">📅 My Bookings & Tracking</a>
            <a href="user_billing.php" class="btn secondary">💰 My Billing</a>
            <a href="logout.php" class="btn danger">🚪 Logout</a>
        </div>
    </div>
</div>

</body>
</html>