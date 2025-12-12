<?php
session_start();
include 'includes/db.php';

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// user_id from session
$user_id = $_SESSION['user_id'];

// Accept service_id via POST or GET
if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
} elseif (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
} else {
    echo "Invalid Request";
    exit();
}

// Fetch service details
$serviceQuery = "SELECT * FROM services WHERE service_id = '$service_id'";
$serviceResult = mysqli_query($conn, $serviceQuery);
$service = mysqli_fetch_assoc($serviceResult);

if (!$service) {
    echo "Service Not Found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Service</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #5ac8fa, #0066cc);
    background-attachment: fixed;
    margin: 0;
    padding: 0;
}

.card {
    width: 430px;
    margin: 70px auto;
    background: rgba(255, 255, 255, 0.90);
    backdrop-filter: blur(15px);
    padding: 25px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0px 10px 25px rgba(0,0,0,0.2);
}

.card h2 {
    font-size: 28px;
    margin-bottom: 15px;
    font-weight: bold;
}

.service-image-box {
    width: 100%;
    height: 230px;
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 18px;
    background: rgba(240,240,240,0.85);
    padding: 10px;
}

.service-img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 14px;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
    transition: transform 0.3s ease-in-out;
}
.service-img:hover {
    transform: scale(1.05);
}

.service-title {
    font-size: 26px;
    text-transform: capitalize;
    font-weight: bold;
    margin-bottom: 8px;
}

.service-price {
    font-size: 18px;
    margin: 6px 0;
}
.service-price span {
    color: #007b5e;
    font-weight: bold;
    font-size: 20px;
}

.service-desc {
    font-size: 15px;
    opacity: 0.8;
    margin-bottom: 25px;
}

input {
    width: 92%;
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
}

.btn {
    width: 94%;
    padding: 13px;
    border: none;
    border-radius: 30px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    background: linear-gradient(to right, #00a884, #00cba9);
    color: white;
}
.btn:hover {
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="card">
    <h2>📌 Book Your Service</h2>

    <div class="service-image-box">
        <img src="images/<?php echo $service['image']; ?>" class="service-img" alt="Service Image">
    </div>

    <h3 class="service-title"><?php echo $service['title']; ?></h3>
    <p class="service-price">💰 Price: <span>₹<?php echo $service['price']; ?></span></p>
    <p class="service-desc">📄 Description: <?php echo $service['description']; ?></p>

    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <input type="text" name="address" placeholder="Enter your full address" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="pincode" placeholder="PIN Code" required>

        <button type="submit" class="btn">✅ Confirm Booking</button>
    </form>
</div>

</body>
</html>