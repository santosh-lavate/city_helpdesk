<?php
// Database connection
$servername = "localhost";
$username   = "root";
$password   = ""; // leave empty for default XAMPP
$dbname     = "city_services";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>