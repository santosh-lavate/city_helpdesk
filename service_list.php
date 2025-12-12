<?php
session_start();
include 'includes/db.php';

// If user not logged in → redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all services
$serviceQuery = "SELECT * FROM services ORDER BY service_id DESC";
$serviceResult = mysqli_query($conn, $serviceQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select a Service</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(to bottom right, #4facfe, #00f2fe);
    margin: 0;
    padding: 35px 0;
}

/*** MAIN CARD WRAPPER ***/
.container {
    width: 90%;
    max-width: 1200px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 22px;
    text-align: center;
    box-shadow: rgba(0,0,0,0.15) 0px 10px 30px;
}

/*** TITLE ***/
h2 {
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 12px;
    color: #023e8a;
}

/*** GRID LAYOUT ***/
.services-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    padding-top: 15px;
}

/*** SERVICE CARD ***/
.service-card {
    background: #ffffff;
    padding: 15px;
    border-radius: 20px;
    box-shadow: rgba(0,0,0,0.13) 0px 8px 20px;
    transition: transform .25s ease-in-out, box-shadow .25s ease-in-out;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: rgba(0,0,0,0.25) 0px 15px 35px;
}

/*** IMAGE STYLE ***/
.service-card img {
    width: 100%;
    height: 170px;
    border-radius: 16px;
    object-fit: cover;
}

/*** SERVICE TITLE ***/
.service-card h3 {
    margin-top: 12px;
    font-size: 18px;
    font-weight: 600;
    color: #003566;
}

/*** BOOK BUTTON ***/
.book-btn {
    display: inline-block;
    padding: 10px 16px;
    background: #0077b6;
    color: white;
    font-size: 14px;
    font-weight: 500;
    border-radius: 10px;
    margin-top: 10px;
    text-decoration: none;
    transition: background .25s ease-in-out;
}

.book-btn:hover {
    background: #023e8a;
}

/*** BACK BUTTON ***/
.back-btn {
    display: inline-block;
    margin-top: 28px;
    padding: 10px 18px;
    background: #ff7b00;
    color: white;
    font-weight: 600;
    text-decoration: none;
    border-radius: 10px;
}

.back-btn:hover {
    background: #e56c00;
}
</style>
</head>
<body>

<div class="container">
    <h2>⚡ Select a Service</h2>

    <div class="services-container">
        <?php while ($service = mysqli_fetch_assoc($serviceResult)) { ?>
            <div class="service-card">

                <?php
                $imgPath = $service['image'] != "" ? "images/" . $service['image'] : "images/default-service.jpg";
                ?>

                <img src="<?php echo $imgPath; ?>" alt="<?php echo $service['title']; ?>">

                <h3><?php echo $service['title']; ?></h3>

                <!-- ✅ Correct link with service_id -->
                <a href="book_service.php?service_id=<?php echo $service['service_id']; ?>" class="book-btn">
                    Book Service
                </a>
            </div>
        <?php } ?>
    </div>

    <a href="user_dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>