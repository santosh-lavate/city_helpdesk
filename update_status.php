<?php
session_start();
header('Content-Type: application/json');
include('includes/db.php');

if (!isset($_SESSION['provider_id'])) {
    echo json_encode(['success'=>false,'error'=>'unauthenticated']);
    exit;
}

$provider_id = $_SESSION['provider_id'];
$booking_id = $_POST['booking_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$booking_id || !$action) {
    echo json_encode(['success'=>false,'error'=>'invalid parameters']);
    exit;
}

// confirm that this booking belongs to a service of this provider
$check = mysqli_query($conn, "SELECT b.*, s.provider_id FROM bookings b JOIN services s ON b.service_id = s.service_id WHERE b.booking_id='".mysqli_real_escape_string($conn,$booking_id)."' LIMIT 1");
if (!$check || mysqli_num_rows($check) == 0) {
    echo json_encode(['success'=>false,'error'=>'booking not found']);
    exit;
}
$row = mysqli_fetch_assoc($check);
if ($row['provider_id'] != $provider_id) {
    echo json_encode(['success'=>false,'error'=>'not allowed']);
    exit;
}

$new_status = null;
if ($action === 'accept') $new_status = 'accepted';
if ($action === 'reject') $new_status = 'rejected';
if ($action === 'complete') $new_status = 'completed';

if (!$new_status) {
    echo json_encode(['success'=>false,'error'=>'invalid action']);
    exit;
}

$upd = mysqli_query($conn, "UPDATE bookings SET status='".mysqli_real_escape_string($conn,$new_status)."' WHERE booking_id='".mysqli_real_escape_string($conn,$booking_id)."'");
if ($upd) {
    echo json_encode(['success'=>true,'new_status'=>$new_status]);
} else {
    echo json_encode(['success'=>false,'error'=>mysqli_error($conn)]);
}