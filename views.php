<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['provider_id'])) {
    header('Location: provider_login.php');
    exit;
}

// accept service_id via POST (from dashboard) or GET (direct link)
$service_id = $_POST['service_id'] ?? $_GET['service_id'] ?? null;
if (!$service_id) {
    die('Invalid request: service_id missing');
}

// fetch service meta
$sq = "SELECT * FROM services WHERE service_id = '".mysqli_real_escape_string($conn,$service_id)."' LIMIT 1";
$sr = mysqli_query($conn,$sq);
$service = mysqli_fetch_assoc($sr);

// fetch reviews: join reviews -> bookings -> users (reviewer)
$q = "
SELECT r.*, u.name as username
FROM reviews r
JOIN bookings b ON r.booking_id = b.booking_id
JOIN users u ON b.user_id = u.user_id
WHERE b.service_id = '".mysqli_real_escape_string($conn,$service_id)."'
ORDER BY r.created_at DESC
";
$res = mysqli_query($conn,$q);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Service Reviews</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#480ca8,#3a0ca3);color:#fff;min-height:100vh;padding:30px}
  .box{max-width:760px;margin:auto;background:rgba(255,255,255,0.12);padding:20px;border-radius:12px;backdrop-filter:blur(8px)}
  h1{margin:0 0 12px}
  .back{display:inline-block;padding:8px 12px;background:#00b4d8;color:#fff;border-radius:8px;text-decoration:none;margin-bottom:12px}
  .review{background:#fff;padding:12px;border-radius:8px;color:#222;margin-bottom:12px}
  .meta{font-size:13px;color:#666;margin-bottom:8px}
  .norev{color:#e6f6ff}
</style>
</head>
<body>
<div class="box">
  <a class="back" href="provider_dashboard.php">⬅ Back to Dashboard</a>
  <h1>Reviews for: <?=htmlspecialchars($service['title'] ?? 'Service')?></h1>

  <?php if ($res && mysqli_num_rows($res) > 0): ?>
    <?php while($r = mysqli_fetch_assoc($res)): ?>
      <div class="review">
        <div class="meta"><strong><?=htmlspecialchars($r['username'])?></strong> • <?=htmlspecialchars($r['created_at'])?> • Rating: <?=intval($r['rating'])?> /5</div>
        <div><?=nl2br(htmlspecialchars($r['comment']))?></div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="norev">No reviews yet for this service.</p>
  <?php endif; ?>
</div>
</body>
</html>