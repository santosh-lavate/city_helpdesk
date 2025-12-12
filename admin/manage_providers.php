<?php
include('../includes/db.php');
 
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM providers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Providers</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Manage Providers</h2>
    <a href="dashboard.php">⬅ Back to Dashboard</a><br><br>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Service Type</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $status = $row['verified'] ? "✅ Verified" : "❌ Not Verified";
            echo "<tr>
                    <td>{$row['provider_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['service_type']}</td>
                    <td>$status</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>