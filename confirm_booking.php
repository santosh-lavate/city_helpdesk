<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // always take from session
$service_id = $_POST['service_id'];
$address = $_POST['address'];
$city = $_POST['city'];
$pincode = $_POST['pincode'];

// Fetch price + provider_id of service
$serviceQuery = "SELECT price, provider_id FROM services WHERE service_id = '$service_id'";
$serviceResult = mysqli_query($conn, $serviceQuery);
$service = mysqli_fetch_assoc($serviceResult);

if (!$service) {
    die("⛔ Service not found!");
}

$price = $service['price'];
$provider_id = $service['provider_id'];

// Insert booking
$insertQuery = "INSERT INTO bookings (user_id, service_id, provider_id, address, city, pincode, status, price)
VALUES ('$user_id', '$service_id', '$provider_id', '$address', '$city', '$pincode', 'pending', '$price')";

if (mysqli_query($conn, $insertQuery)) {
    echo "<script>
            alert('✅ Booking Confirmed Successfully!');
            window.location.href='user_tracking.php';
        </script>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>