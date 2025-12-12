<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookings + provider info
$query = "SELECT b.*, s.title, s.price, p.name AS provider_name, p.phone AS provider_phone 
          FROM bookings b
          JOIN services s ON b.service_id = s.service_id
          JOIN providers p ON b.provider_id = p.provider_id
          WHERE b.user_id = '$user_id'
          ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Service Tracking</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif;}

body {
    background: linear-gradient(135deg, #00b4d8, #0077b6);
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: auto;
}

.card {
    background: #ffffffdd;
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.title {
    font-size: 22px;
    font-weight: 600;
    color: #023e8a;
}

.info {
    margin-top: 10px;
    color: #333;
}

.status-container {
    margin-top: 20px;
}

.steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.step-box {
    width: 32%;
    padding: 10px;
    text-align: center;
    font-weight: 600;
    border-radius: 10px;
    background: #e0f4ff;
    border: 2px solid #caf0f8;
    color: #555;
}

.step-active {
    background: #0077b6;
    border-color: #023e8a;
    color: white;
}

.progress-line {
    height: 5px;
    background: #caf0f8;
    width: 100%;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 20px;
}

.progress-fill {
    height: 5px;
    background: #0077b6;
}

.review-btn {
    background: #06d6a0;
    color: white;
    padding: 12px;
    width: 100%;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 15px;
}
.review-btn:hover {
    background: #059669;
}
</style>
</head>

<body>

<div class="container">

<h2 style="color:white; text-align:center; margin-bottom:20px;">📦 My Service Tracking</h2>

<?php while ($row = mysqli_fetch_assoc($result)) { 

// convert status to lowercase (IMPORTANT)
$status = strtolower($row['status']);

// progress bar width
$progress = "0%";
if ($status == "accepted") $progress = "50%";
if ($status == "completed") $progress = "100%";

?>

<div class="card">

    <div class="title"><?= $row['title']; ?> (₹<?= $row['price']; ?>)</div>

    <div class="info">
        <b>Provider:</b> <?= $row['provider_name']; ?><br>
        <b>Phone:</b> <?= $row['provider_phone']; ?><br>
        <b>Date:</b> <?= $row['booking_date']; ?><br>
    </div>

    <div class="status-container">

        <div class="progress-line">
            <div class="progress-fill" style="width: <?= $progress ?>;"></div>
        </div>

        <div class="steps">
            <div class="step-box <?= ($status=='pending'?'step-active':'') ?>">Pending</div>
            <div class="step-box <?= ($status=='accepted'?'step-active':'') ?>">Accepted</div>
            <div class="step-box <?= ($status=='completed'?'step-active':'') ?>">Completed</div>
        </div>

    </div>

    <?php if ($status == "completed") { ?>
        <form method="POST" action="review.php">
            <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
            <button class="review-btn">⭐ Leave Review</button>
        </form>
    <?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>