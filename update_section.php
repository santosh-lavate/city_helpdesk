<?php
session_start();
include('includes/db.php');  // 🔥 Correct path

if (!isset($_SESSION['provider_id'])) {
    header("Location: provider_login.php");
    exit();
}

if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    if (isset($_POST['accept'])) {
        $status = 'accepted';
    } elseif (isset($_POST['reject'])) {
        $status = 'rejected';
    } elseif (isset($_POST['complete'])) {
        $status = 'completed';
    }

    $query = "UPDATE bookings SET status='$status' WHERE booking_id='$booking_id'";
    mysqli_query($conn, $query);

    header("Location: provider_dashboard.php");
    exit();
}
?>