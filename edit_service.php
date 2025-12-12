<?php
session_start();
include("includes/db.php");

// ✅ Check if service_id is coming from POST (from provider dashboard)
if (!isset($_POST['service_id'])) {
    echo "Service ID missing!";
    exit();
}

$service_id = $_POST['service_id'];

// ✅ Fetch service details
$query = "SELECT * FROM services WHERE service_id = '$service_id'";
$result = mysqli_query($conn, $query);
$service = mysqli_fetch_assoc($result);

if (!$service) {
    echo "Service not found!";
    exit();
}

// ✅ Process update request
if (isset($_POST['update_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "images/" . $image);

        $update = "UPDATE services SET title='$title', description='$description', price='$price', image='$image'
                   WHERE service_id = '$service_id'";
    } else {
        $update = "UPDATE services SET title='$title', description='$description', price='$price'
                   WHERE service_id = '$service_id'";
    }

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('✅ Service Updated Successfully!'); window.location='provider_dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to update service');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Service</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
    font-family: "Poppins", sans-serif;
}
body{
    margin:0;
    padding:0;
    background:linear-gradient(135deg,#0077b6,#023e8a);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.card{
    width:420px;
    background:rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    padding:25px;
    border-radius:16px;
    color:#fff;
    box-shadow:0px 4px 20px rgba(0,0,0,0.3);
}
.card h2{
    text-align:center;
    margin-bottom:20px;
}
input, textarea{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:none;
    margin-bottom:12px;
    font-size:14px;
}
button{
    width:100%;
    padding:12px;
    border:none;
    background:#00b4d8;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}
button:hover{
    background:#0077b6;
}
.back{
    display:block;
    text-align:center;
    color:#fff;
    margin-top:12px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="card">
    <h2>✏ Edit Service</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="service_id" value="<?php echo $service['service_id']; ?>">

        <label>Service Title</label>
        <input type="text" name="title" value="<?php echo $service['title']; ?>" required>

        <label>Description</label>
        <textarea name="description" rows="3" required><?php echo $service['description']; ?></textarea>

        <label>Price (₹)</label>
        <input type="number" name="price" value="<?php echo $service['price']; ?>" required>

        <label>Change Image (Optional)</label>
        <input type="file" name="image">

        <button type="submit" name="update_service">Update</button>
    </form>

    <a href="provider_dashboard.php" class="back">⬅ Back to Dashboard</a>
</div>

</body>
</html>