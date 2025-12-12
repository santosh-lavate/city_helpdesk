<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['provider_id'])) {
    header('Location: provider_login.php');
    exit;
}

$provider_id = $_SESSION['provider_id'];
$provider_name = $_SESSION['provider_name'];

// ============================ ADD SERVICE =============================
$add_msg = '';
if (isset($_POST['add_service'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Image Upload
    $image_filename = null;
    if (!empty($_FILES['service_image']['name'])) {
        $file = $_FILES['service_image'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $image_filename = time() . "_" . rand(1000,9999) . "." . $ext;
        move_uploaded_file($file['tmp_name'], "images/".$image_filename);
    }

    $insert = "INSERT INTO services(provider_id,title,description,price,status,image,created_at)
               VALUES('$provider_id','$title','$description','$price','active','$image_filename',NOW())";

    mysqli_query($conn, $insert);
    header("Location: provider_dashboard.php");
    exit;
}

// ========================= FETCH SERVICES ============================
$with_reviews_q = "
SELECT s.*, 
       (SELECT COUNT(*) FROM reviews r 
        JOIN bookings b ON r.booking_id=b.booking_id 
        WHERE b.service_id=s.service_id) AS review_count
FROM services s
WHERE s.provider_id='$provider_id'
ORDER BY s.service_id DESC
";

$with_reviews = mysqli_query($conn, $with_reviews_q);

$without_reviews_q = "
SELECT s.*, 
       (SELECT COUNT(*) FROM reviews r 
        JOIN bookings b ON r.booking_id=b.booking_id 
        WHERE b.service_id=s.service_id) AS review_count
FROM services s
WHERE s.provider_id='$provider_id'
HAVING review_count = 0 OR review_count IS NULL
ORDER BY s.service_id DESC
";

$without_reviews = mysqli_query($conn, $without_reviews_q);

// ========================= FETCH BOOKINGS ============================
$bookings_q = "
SELECT b.*, u.name AS username, s.title, s.price 
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN services s ON b.service_id = s.service_id
WHERE s.provider_id = '$provider_id'
ORDER BY b.booking_date DESC
";

$result_bookings = mysqli_query($conn, $bookings_q);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Provider Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
  *{box-sizing:border-box;font-family:'Poppins',sans-serif}
  body{margin:0;background:linear-gradient(135deg,#0077b6,#023e8a);color:#fff;min-height:100vh;padding:30px}
  .logout{position:fixed;right:24px;top:18px;background:#ff4d4d;padding:10px 14px;border-radius:8px;color:#fff;text-decoration:none}
  h1{ text-align:center;margin-bottom:18px;font-weight:600 }
  .grid{display:grid;grid-template-columns:1fr 420px 420px;gap:20px;align-items:start;max-width:1250px;margin:0 auto}
  .card{background:rgba(255,255,255,0.10);padding:18px;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,0.15)}
  .add-form input, .add-form textarea { width:100%;padding:10px;border-radius:8px;border:none;margin:8px 0; }
  .btn{background:#00b4d8;color:#fff;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
  .btn-danger{background:#ff6b6b}
  .scroll{max-height:350px;overflow:auto;padding-right:6px}
  .service-card{background:#fff;padding:12px;border-radius:12px;color:#16324a;margin-bottom:12px}
  .service-thumb img{width:110px;height:80px;border-radius:10px;object-fit:cover}
  .booking-box{background:#fff;color:#000;padding:12px;border-radius:12px;margin-bottom:12px}
</style>
</head>

<body>

<a class="logout" href="logout.php">Logout</a>

<h1>👋 Welcome <span style="color:#90e0ef"><?php echo $provider_name; ?></span></h1>

<div class="grid">

  <!-- ADD NEW SERVICE -->
  <div class="card">
    <h2>Add New Service</h2>
    <form class="add-form" method="POST" enctype="multipart/form-data">
      <label>Service Title</label>
      <input type="text" name="title" required>

      <label>Description</label>
      <textarea name="description" rows="3" required></textarea>

      <label>Price (₹)</label>
      <input type="number" name="price" required>

      <label>Service Image (optional)</label>
      <input type="file" name="service_image">

      <button type="submit" name="add_service" class="btn">Add Service</button>
    </form>
  </div>

  <!-- SERVICES WITH REVIEWS -->
<div class="card">
    <h2 style="margin-top:0;color:#fff">⭐ Services With Reviews</h2>
    <div class="scroll">
        
        <?php if($with_reviews && mysqli_num_rows($with_reviews) > 0){ ?>
        
            <?php while($row = mysqli_fetch_assoc($with_reviews)){ ?>
            
            <div class="service-card" style="display:flex; gap:15px; align-items:center; padding:12px;">
                
                <!-- Thumbnail -->
                <div class="service-thumb">
                    <?php
                        $img = ($row['image'] && file_exists(__DIR__.'/images/'.$row['image']))
                                ? 'images/'.$row['image']
                                : 'images/default-service.jpg';
                    ?>
                    <img src="<?= $img ?>" style="width:100px; height:75px; border-radius:10px; object-fit:cover;">
                </div>

                <!-- Details -->
                <div style="flex:1;">
                    <h3 style="margin:0; color:#023e8a;"><?= $row['title']; ?></h3>
                    <p style="margin:4px 0; color:#333;"><?= $row['description']; ?></p>
                    <div style="color:#0077b6; font-weight:600;">₹ <?= $row['price']; ?></div>
                </div>

                <!-- Buttons -->
                <div>
                    <form method="post" action="edit_service.php">
                        <input type="hidden" name="service_id" value="<?= $row['service_id']; ?>">
                        <button class="btn" type="submit">✏ Edit</button>
                    </form>

                    <form method="post" action="delete_service.php" onsubmit="return confirm('Delete this service?');">
                        <input type="hidden" name="service_id" value="<?= $row['service_id']; ?>">
                        <button class="btn btn-danger" type="submit">🗑 Delete</button>
                    </form>

                    <!-- ⭐ REVIEWS BUTTON (IMPORTANT) -->
                    <button class="btn" onclick="window.location.href='views.php?service_id=<?= $row['service_id']; ?>'">
                        ⭐ Reviews (<?= $row['review_count']; ?>)
                    </button>
                </div>

            </div>

            <?php } ?>
        
        <?php } else { ?>
            <p>No reviewed services yet.</p>
        <?php } ?>
    
    </div>
</div>

  <!-- SERVICES WITHOUT REVIEWS -->
<div class="card">
    <h2 style="margin-top:0;color:#fff">🕒 Services Without Reviews</h2>
    <div class="scroll">

        <?php if($without_reviews && mysqli_num_rows($without_reviews) > 0){ ?>
        
            <?php while($row = mysqli_fetch_assoc($without_reviews)){ ?>

            <div class="service-card" style="display:flex; gap:15px; align-items:center; padding:12px;">

                <!-- Thumbnail Fix -->
                <div class="service-thumb">
                    <?php
                        $img = ($row['image'] && file_exists(__DIR__ . '/images/' . $row['image']))
                                ? 'images/' . $row['image']
                                : 'images/default-service.jpg';
                    ?>
                    <img src="<?= $img ?>" 
                         style="width:100px; height:75px; border-radius:10px; object-fit:cover;">
                </div>

                <!-- Text -->
                <div style="flex:1;">
                    <h3 style="margin:0; color:#023e8a;"><?= $row['title']; ?></h3>
                    <p style="margin:4px 0; color:#333;"><?= $row['description']; ?></p>
                    <div style="color:#0077b6; font-weight:600;">₹ <?= $row['price']; ?></div>
                </div>

                <!-- Buttons -->
                <div>
                    <form method="post" action="edit_service.php">
                        <input type="hidden" name="service_id" value="<?= $row['service_id']; ?>">
                        <button class="btn" type="submit">✏ Edit</button>
                    </form>

                    <form method="post" action="delete_service.php" 
                          onsubmit="return confirm('Delete this service?');">
                        <input type="hidden" name="service_id" value="<?= $row['service_id']; ?>">
                        <button class="btn btn-danger" type="submit">🗑 Delete</button>
                    </form>
                </div>

            </div>

            <?php } ?>

        <?php } else { ?>
            <p>No services without reviews.</p>
        <?php } ?>

    </div>
</div>

<!-- ================================ BOOKINGS ================================ -->

<!-- ⭐ BEAUTIFUL INCOMING BOOKINGS CARD UI -->
<div class="card" style="max-width:1250px; margin:20px auto;">
    <h2 style="margin-top:0; color:#fff;">📦 Incoming Bookings</h2>

    <div style="max-height:350px; overflow-y:auto; padding-right:10px;">

        <?php if(mysqli_num_rows($result_bookings) > 0){ ?>
        
            <?php while($b = mysqli_fetch_assoc($result_bookings)){ ?>

            <div style="
                background:#ffffff;
                border-radius:16px;
                padding:18px;
                margin-bottom:14px;
                box-shadow:0 6px 18px rgba(0,0,0,0.18);
                border-left:6px solid <?= 
                    strtolower($b['status'])=='pending' ? '#ffb703' :
                    (strtolower($b['status'])=='accepted' ? '#219ebc' : '#38b000'); 
                ?>;
            ">

                <h3 style="margin:0; font-size:20px; color:#023e8a;">
                    <?= $b['title']; ?> (₹<?= $b['price']; ?>)
                </h3>

                <p style="margin:6px 0; color:#333;">
                    <b>User:</b> <?= $b['username']; ?><br>
                    <b>Address:</b> <?= $b['address']; ?>, <?= $b['city']; ?> (<?= $b['pincode']; ?>)
                </p>

                <p style="font-weight:600; margin-top:5px;">
                    <b>Status:</b> 
                    <span style="color:
                        <?= strtolower($b['status'])=='pending' ? '#fb8500' : 
                           (strtolower($b['status'])=='accepted' ? '#0077b6' : '#2d6a4f'); ?>;
                    ">
                    <?= ucfirst($b['status']); ?>
                    </span>
                </p>

                <form method="POST" action="update_section.php" style="margin-top:10px;">
                    <input type="hidden" name="booking_id" value="<?= $b['booking_id']; ?>">

                    <?php if(strtolower($b['status'])=="pending"){ ?>
                        <button name="accept" class="btn"
                            style="background:#219ebc; margin-right:10px;">Accept</button>
                        <button name="reject" class="btn btn-danger">Reject</button>
                    <?php } ?>

                    <?php if(strtolower($b['status'])=="accepted"){ ?>
                        <button name="complete" class="btn"
                            style="background:#2d6a4f;">Mark Completed</button>
                    <?php } ?>

                </form>
            </div>

            <?php } ?>

        <?php } else { ?>

            <p style="color:white; font-size:18px; text-align:center;">No bookings yet.</p>

        <?php } ?>

    </div>
</div>


</body>
</html>