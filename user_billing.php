<?php
session_start();
include_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "SELECT billing.*, services.title AS service_name, providers.name AS provider_name
          FROM billing
          INNER JOIN bookings ON billing.booking_id = bookings.booking_id
          INNER JOIN services ON bookings.service_id = services.service_id
          INNER JOIN providers ON bookings.provider_id = providers.provider_id
          WHERE billing.user_id = '$user_id'
          ORDER BY billing.bill_id DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Billing | City Services</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(120deg, #0099ff, #006aff, #003bff);
        background-size: 400% 400%;
        animation: gradientBG 8s ease infinite;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .container {
        width: 85%;
        max-width: 900px;
        margin: auto;
        padding: 40px 0px;
    }

    .title {
        color: white;
        font-size: 36px;
        font-weight: 800;
        text-align: center;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        gap: 10px;
        align-items: center;
    }

    .card {
        backdrop-filter: blur(18px);
        background: rgba(255, 255, 255, 0.2);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        color: #fff;
        animation: fadeIn 0.7s ease-in-out;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.35);
    }

    @keyframes fadeIn {
        from { transform: translateY(15px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .bill-title {
        font-size: 22px;
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .info {
        margin: 6px 0;
        font-size: 17px;
    }

    .info span {
        opacity: 0.9;
        font-weight: 400;
    }

    .status {
        padding: 8px 16px;
        border-radius: 25px;
        display: inline-block;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
    }

    .pending { background: #ff9800; }
    .completed { background: #00e676; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        background: #ffffff;
        color: #0077ff;
        padding: 12px 18px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
        background: #dce9ff;
        transform: scale(1.04);
    }

</style>
</head>

<body>
<div class="container">

    <h2 class="title"><i class="fa-solid fa-file-invoice"></i> My Billing & Invoices</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="card">
                <div class="bill-title">
                    <i class="fa-solid fa-receipt"></i> Invoice #<?= $row['bill_id']; ?>
                </div>

                <div class="info"><b>Service:</b> <span><?= $row['service_name']; ?></span></div>
                <div class="info"><b>Provider:</b> <span><?= $row['provider_name']; ?></span></div>
                <div class="info"><b>Amount:</b> <span>₹<?= number_format($row['amount'], 2); ?></span></div>

                <div class="info"><b>Status:</b>
                    <?php if ($row['payment_status'] == "Pending") { ?>
                        <span class="status pending"><i class="fa-solid fa-clock"></i> Pending</span>
                    <?php } else { ?>
                        <span class="status completed"><i class="fa-solid fa-check-circle"></i> Paid</span>
                    <?php } ?>
                </div>

                <div class="info"><b>Date:</b> <span><?= $row['payment_date']; ?></span></div>

                <a href="download_invoice.php?bill_id=<?= $row['bill_id']; ?>" class="btn">
                    <i class="fa-solid fa-download"></i> Download Invoice
                </a>
            </div>

        <?php } ?>

    <?php } else { ?>
        <p style="color: white; text-align:center; font-size:18px;">⚠ No billing records found yet.</p>
    <?php } ?>

</div>
</body>
</html>