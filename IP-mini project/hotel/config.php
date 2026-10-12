<?php
session_start();
$conn = new mysqli("localhost", "root", "", "hotel_db");   // change user/password if needed
if ($conn->connect_error) { die("Database connection failed: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");

// ---- Hotel details: edit these to your real details ----
define('HOTEL_NAME', 'S.S.R Hotel');
define('HOTEL_ADDRESS', 'No. 12, Main Road, Your Town, Tamil Nadu - 600001');
define('HOTEL_PHONE', '+91 98765 43210');
define('HOTEL_EMAIL', 'contact@ssrhotel.com');
define('FOOD_PRICE', 350);   // food charge per person per night (Rs)

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function require_login() { if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; } }
function require_admin() { if (($_SESSION['role'] ?? '') !== 'admin') { header("Location: ../login.php"); exit; } }

// Finds images/<name>.jpg|jpeg|png|webp|svg. Put your real photos with the same name to replace the sample ones.
function img($name, $base = '') {
  foreach (['jpg','jpeg','png','webp','svg'] as $x) {
    if (file_exists(__DIR__ . "/images/$name.$x")) return $base . "images/$name.$x";
  }
  return $base . "images/hotel_outside_1.jpg";
}
