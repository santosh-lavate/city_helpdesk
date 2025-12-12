<?php
session_start();
include('includes/db.php');

// ✅ Check bill_id instead of booking_id
if (!isset($_GET['bill_id'])) {
    die('Invalid request!');
}

$bill_id = mysqli_real_escape_string($conn, $_GET['bill_id']);

// ✅ Fetch billing + booking + service + user + provider
$query = "SELECT billing.*, bookings.booking_date, services.title AS service_title, services.price, 
                 users.name AS user_name, users.phone AS user_phone, users.address AS user_address,
                 providers.name AS provider_name, providers.phone AS provider_phone
          FROM billing
          JOIN bookings ON billing.booking_id = bookings.booking_id
          JOIN services ON bookings.service_id = services.service_id
          JOIN users ON billing.user_id = users.user_id
          JOIN providers ON billing.provider_id = providers.provider_id
          WHERE billing.bill_id = '$bill_id'
          LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Bill not found!");
}

$bill = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice</title>
<style>
body { font-family: Arial; background: #f2f2f2; }
.invoice-container {
    max-width: 800px;
    margin: 30px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
h2 { border-bottom: 2px solid #000; padding-bottom: 8px; }
p { font-size: 16px; }
.btn-print {
    display: inline-block;
    padding: 8px 16px;
    background: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}
</style>
</head>
<body>

<div class="invoice-container">
    <h2>Invoice #<?= htmlspecialchars($bill['bill_id']) ?></h2>

    <h3>Service Details</h3>
    <p><strong>Service:</strong> <?= htmlspecialchars($bill['service_title']) ?></p>
    <p><strong>Provider:</strong> <?= htmlspecialchars($bill['provider_name']) ?> (<?= htmlspecialchars($bill['provider_phone']) ?>)</p>

    <h3>User Details</h3>
    <p><strong>Name:</strong> <?= htmlspecialchars($bill['user_name']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($bill['user_phone']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($bill['user_address']) ?></p>

    <h3>Billing Details</h3>
    <p><strong>Amount:</strong> ₹<?= htmlspecialchars($bill['amount']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($bill['payment_status']) ?></p>
    <p><strong>Payment Date:</strong> <?= htmlspecialchars($bill['payment_date']) ?></p>

    <hr>
    <p style="text-align: right;"><strong>Total: ₹<?= htmlspecialchars($bill['amount']) ?></strong></p>

    <a class="btn-print" href="javascript:window.print()">Print Invoice</a>
</div>

</body>
</html>