<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['provider_id'])) { header('Location: provider_login.php'); exit; }
$provider_id = $_SESSION['provider_id'];
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: provider_dashboard.php'); exit; }

// verify ownership
$res = mysqli_query($conn, "SELECT provider_id FROM services WHERE service_id='".mysqli_real_escape_string($conn,$id)."' LIMIT 1");
if (!$res || mysqli_num_rows($res)==0) { header('Location: provider_dashboard.php'); exit; }
$row = mysqli_fetch_assoc($res);
if ($row['provider_id'] != $provider_id) { die('Not allowed'); }

// delete (you might want to soft-delete in production)
mysqli_query($conn, "DELETE FROM services WHERE service_id='".mysqli_real_escape_string($conn,$id)."'");
header('Location: provider_dashboard.php');
exit;