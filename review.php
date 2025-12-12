<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// booking_id must come from POST
if (!isset($_POST['booking_id'])) {
    die("<h3 style='text-align:center;color:red;'>Invalid Booking.</h3>");
}

$booking_id = intval($_POST['booking_id']);

// Fetch booking info
$query = "SELECT b.*, s.title, s.price, p.name AS provider_name
          FROM bookings b
          JOIN services s ON b.service_id = s.service_id
          JOIN providers p ON b.provider_id = p.provider_id
          WHERE b.booking_id = '$booking_id'
          AND b.user_id = '$user_id'
          LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("<h3 style='text-align:center;color:red;'>No booking found!</h3>");
}

$booking = mysqli_fetch_assoc($result);

// Review allowed only when status is completed
if (strtolower($booking['status']) !== "completed") {
    die("<h3 style='text-align:center;color:red;'>You can review only completed services.</h3>");
}

// Check if already reviewed
$check = mysqli_query($conn, "SELECT * FROM reviews WHERE booking_id = '$booking_id'");
$already_reviewed = mysqli_num_rows($check) > 0;

$message = "";

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review']) && !$already_reviewed) {

    $rating  = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        $message = "<p class='error'>Rating must be between 1 and 5.</p>";
    } else {
        $insert = "INSERT INTO reviews (booking_id, user_id, rating, comment, created_at)
                   VALUES ('$booking_id', '$user_id', '$rating', '$comment', NOW())";

        if (mysqli_query($conn, $insert)) {
            $message = "<p class='success'>⭐ Review submitted successfully!</p>";
            $already_reviewed = true;
        } else {
            $message = "<p class='error'>❌ Error submitting review: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Leave a Review</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #0093E9, #80D0C7);
    color: #333;
    margin: 0;
    padding: 0;
}
.container {
    width: 90%;
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    text-align: center;
}
h2 {
    color: #0078d7;
}
p.success {
    color: green;
    font-weight: 600;
}
p.error {
    color: red;
    font-weight: 600;
}
.rating {
    display: flex;
    justify-content: center;
    margin: 15px 0;
}
.rating input {
    display: none;
}
.rating label {
    font-size: 35px;
    color: #ccc;
    cursor: pointer;
    transition: 0.3s;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffb400;
}
textarea {
    width: 90%;
    height: 80px;
    margin-top: 10px;
    border: 1px solid #aaa;
    padding: 10px;
    border-radius: 10px;
    resize: none;
}
button {
    background: #0078d7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    margin-top: 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background: #005fa3;
}
.review-box {
    margin-top: 25px;
    text-align: left;
}
.review-item {
    background: #f1f5f9;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
}
.back {
    text-decoration: none;
    color: #0078d7;
    font-weight: 600;
}
</style>
</head>
<body>

<div class="container">
    <h2>⭐ Leave a Review</h2>

    <p><strong>Service:</strong> <?= htmlspecialchars($booking['title']); ?> (₹<?= $booking['price']; ?>)</p>
    <p><strong>Provider:</strong> <?= htmlspecialchars($booking['provider_name']); ?></p>

    <?= $message ?>

    <?php if (!$already_reviewed): ?>
    <form method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking_id; ?>">

        <div class="rating">
            <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
            <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
            <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
            <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
            <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
        </div>

        <textarea name="comment" placeholder="Write your feedback..." required></textarea>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
    <?php else: ?>
        <p class="success">You’ve already reviewed this booking ✔</p>
    <?php endif; ?>

    <br>
    <a href="user_tracking.php" class="back">← Back to Tracking</a>

    <br><br>

    <?php
    $reviews = mysqli_query($conn, "SELECT * FROM reviews WHERE booking_id='$booking_id' ORDER BY created_at DESC");

    if (mysqli_num_rows($reviews) > 0) {
        echo "<div class='review-box'><h3>⭐ Previous Reviews</h3>";
        while ($rev = mysqli_fetch_assoc($reviews)) {
            echo "<div class='review-item'>
                    <strong>Rating:</strong> " . str_repeat("⭐", $rev['rating']) . "<br>
                    <strong>Comment:</strong> " . htmlspecialchars($rev['comment']) . "<br>
                    <small><i>" . $rev['created_at'] . "</i></small>
                </div>";
        }
        echo "</div>";
    }
    ?>
</div>

</body>
</html>