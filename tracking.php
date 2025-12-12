<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookings and related info
$query = "SELECT b.*, s.title, s.price, p.name AS provider_name, p.phone AS provider_phone 
          FROM bookings b
          JOIN services s ON b.service_id = s.service_id
          JOIN providers p ON b.provider_id = p.provider_id
          WHERE b.user_id = '$user_id'
          ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("<h3 style='color:red;text-align:center;'>❌ SQL Error: " . mysqli_error($conn) . "</h3>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings & Service Tracking</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }

body {
    background: linear-gradient(135deg, #00b4d8, #0077b6);
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: auto;
}

.card {
    background: white;
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: 0.2s;
}
.card:hover {
    transform: scale(1.02);
}

.title {
    font-size: 20px;
    font-weight: 600;
    color: #0077b6;
}

.status-flow {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.status {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
    opacity: 0.4;
    background: #90e0ef;
    color: #023e8a;
}

.status.active {
    background: #0077b6;
    color: white;
    opacity: 1;
}

.review-btn {
    background: #06d6a0;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    margin-top: 15px;
    width: 100%;
    font-size: 14px;
    font-weight: 600;
}
.review-btn:hover {
    background: #059669;
}

.info { margin-top: 8px; color: #333; font-size: 15px; }
</style>
</head>
<body>

<div class="container">

<h2 style="color:white; margin-bottom:15px; text-align:center;">📦 My Service Tracking</h2>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

<div class="card">
    <div class="title"><?= $row['title']; ?> (₹<?= $row['price']; ?>)</div>

    <div class="info">
        <b>Provider:</b> <?= $row['provider_name']; ?><br>
        <b>Phone:</b> <?= $row['provider_phone']; ?><br>
        <b>Date:</b> <?= $row['booking_date']; ?><br>
    </div>

    <!-- Status flow -->
    <div class="status-flow">
        <span class="status <?= $row['status']=="pending"?"active":"" ?>">Pending</span>
        <span class="status <?= $row['status']=="accepted"?"active":"" ?>">Accepted</span>
        <span class="status <?= $row['status']=="completed"?"active":"" ?>">Completed</span>
    </div>

    <?php if ($row['status'] == "completed") { ?>
        <form action="review.php" method="post">
            <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
            <button class="review-btn">⭐Review</button>
        </form>
    <?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>