<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// If booking_id is not passed, redirect
if (!isset($_GET['booking_id'])) {
    header("Location: tracking.php");
    exit();
}

$booking_id = $_GET['booking_id'];

// Get booking details
$booking_query = "
SELECT b.*, p.name AS provider_name, p.provider_id
FROM bookings b
JOIN providers p ON b.provider_id = p.provider_id
WHERE b.booking_id = '$booking_id' AND b.user_id = '$user_id'
";
$booking_result = mysqli_query($conn, $booking_query);
$booking = mysqli_fetch_assoc($booking_result);

$message = "";

if (isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $provider_id = $booking['provider_id'];

    // Insert review
    $query = "INSERT INTO reviews (booking_id, user_id, provider_id, rating, comment)
              VALUES ('$booking_id', '$user_id', '$provider_id', '$rating', '$comment')";
    
    if (mysqli_query($conn, $query)) {
        $message = "✅ Thank you for your review!";
    } else {
        $message = "❌ Failed to submit review.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Review</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Leave a Review for <?php echo $booking['provider_name']; ?></h2>

    <?php if ($message != "") echo "<p style='color:green;'>$message</p>"; ?>

    <form method="POST">
        <label>Rating (1 to 5):</label><br>
        <select name="rating" required>
            <option value="">--Select--</option>
            <option value="1">⭐ 1</option>
            <option value="2">⭐⭐ 2</option>
            <option value="3">⭐⭐⭐ 3</option>
            <option value="4">⭐⭐⭐⭐ 4</option>
            <option value="5">⭐⭐⭐⭐⭐ 5</option>
        </select><br><br>

        <label>Comment:</label><br>
        <textarea name="comment" rows="4" placeholder="Write your feedback..." required></textarea><br><br>

        <button type="submit" name="submit_review">Submit Review</button>
    </form>

    <br>
    <a href="tracking.php">⬅ Back to My Bookings</a>
</div>

</body>
</html>