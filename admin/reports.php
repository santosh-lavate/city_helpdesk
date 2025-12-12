<?php
include('../includes/db.php');
 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$query = "
SELECT b.booking_id, b.status, b.booking_date, 
       u.name AS user_name, p.name AS provider_name, s.title AS service_title
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN providers p ON b.provider_id = p.provider_id
JOIN services s ON b.service_id = s.service_id
ORDER BY b.booking_id DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Booking Reports</h2>
    <a href="dashboard.php">⬅ Back to Dashboard</a><br><br>

    <table border="1" cellpadding="5">
        <tr>
            <th>Booking ID</th>
            <th>User</th>
            <th>Provider</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['booking_id']}</td>
                    <td>{$row['user_name']}</td>
                    <td>{$row['provider_name']}</td>
                    <td>{$row['service_title']}</td>
                    <td>{$row['booking_date']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        ?>
    </table>
    
    

</body>
</html>