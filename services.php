<?php
session_start();
include('includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Services</title>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #e7f0ff;
        font-family: Arial, sans-serif;
    }

    .services-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        margin-top: 25px;
    }

    .service-card {
        width: 320px;
        background: #ffffff;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
        text-align: center;
        transition: 0.3s;
    }

    .service-card:hover {
        transform: scale(1.05);
    }

    .service-img {
        width: 100%;
        height: 220px;
        border-radius: 12px;
        object-fit: cover;
        object-position: center;
    }

    .title-head {
        text-align: center;
        margin-top: 15px;
        font-size: 28px;
        font-weight: bold;
        color: #003566;
    }

    .price {
        font-size: 22px;
        font-weight: bold;
        color: #0077b6;
    }

    .btn-success {
        width: 100%;
        border-radius: 25px;
        font-size: 18px;
        padding: 8px;
    }
</style>

</head>
<body>

<h2 class="title-head">Our Services</h2>

<div class="services-container">

<?php
$sql = "SELECT * FROM services";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $imagePath = "images/" . $row['image'];
?>

    <div class="service-card">
        <img src="<?php echo $imagePath; ?>" class="service-img">

        <h4 style="margin-top: 10px;"><?php echo $row['title']; ?></h4>
        <p><?php echo $row['description']; ?></p>
        <p class="price">₹ <?php echo number_format($row['price'], 2); ?></p>

        <form action="book_service.php" method="post">
            <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">
            <button type="submit" class="btn btn-success">Book Now</button>
        </form>
    </div>

<?php } ?>

</div>
</body>
</html>