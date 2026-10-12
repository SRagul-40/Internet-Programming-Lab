<?php require_once __DIR__ . '/../config.php'; $base = $base ?? ''; ?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? HOTEL_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>css/style.css">
</head><body>
<header class="top"><div class="wrap bar">
  <a class="logo" href="<?= $base ?>index.php">S.S.R <small>Hotel</small></a>
  <nav>
    <a href="<?= $base ?>index.php">Home</a>
    <a href="<?= $base ?>rooms.php">Rooms</a>
    <a href="<?= $base ?>about.php">About &amp; Reviews</a>
    <?php if (isset($_SESSION['uid'])): ?>
      <a href="<?= $base ?>my_bookings.php">My bookings</a>
      <?php if ($_SESSION['role']==='admin'): ?><a href="<?= $base ?>admin/index.php">Admin</a><?php endif; ?>
      <a href="<?= $base ?>logout.php">Log out (<?= e($_SESSION['name']) ?>)</a>
    <?php else: ?>
      <a href="<?= $base ?>login.php">Log in</a>
      <a class="btn small" href="<?= $base ?>register.php">Create account</a>
    <?php endif; ?>
  </nav></div></header>
<main class="wrap">
