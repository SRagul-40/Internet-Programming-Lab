<?php $title = "S.S.R Hotel - Welcome"; include 'includes/header.php';
$rooms = $conn->query("SELECT * FROM rooms ORDER BY price LIMIT 3"); ?>
<section class="hero">
  <h1>Welcome to S.S.R Hotel</h1>
  <p class="lead">Welcome to S.S.R Hotel, your comfortable home away from home. We offer clean, well-maintained AC and Non-AC rooms
  for couples, families and groups, with optional home-style food, TV and balcony-view rooms. Our friendly staff are available
  day and night to make your stay relaxed and memorable. Choose your dates and the number of members, and we will suggest the best rooms for you.</p>
  <form class="search" action="rooms.php" method="get">
    <label>Check-in<input type="date" name="check_in" required></label>
    <label>Check-out<input type="date" name="check_out" required></label>
    <label>Members<input type="number" name="guests" min="1" max="20" value="2" required></label>
    <button class="btn">Find rooms</button>
  </form>
</section>

<h2>Our hotel</h2>
<div class="photos">
  <figure><img src="<?= e(img('hotel_outside_1')) ?>" alt="Outside view of S.S.R Hotel"><figcaption>Outside view</figcaption></figure>
  <figure><img src="<?= e(img('hotel_outside_2')) ?>" alt="Entrance and garden"><figcaption>Entrance and garden</figcaption></figure>
  <figure><img src="<?= e(img('hotel_inside_1')) ?>" alt="Inside lobby"><figcaption>Inside - lobby</figcaption></figure>
  <figure><img src="<?= e(img('hotel_inside_2')) ?>" alt="Rooftop swimming pool"><figcaption>Rooftop swimming pool</figcaption></figure>
</div>

<h2>Popular rooms</h2>
<div class="grid">
<?php while ($r = $rooms->fetch_assoc()): ?>
  <article class="card">
    <img class="cover" src="<?= e(img("rooms/{$r['room_no']}_1")) ?>" alt="Room <?= e($r['room_no']) ?>">
    <h3><?= e($r['type']) ?> · Room <?= e($r['room_no']) ?></h3>
    <p><?= e($r['description']) ?></p>
    <p class="price">₹<?= number_format($r['price']) ?> <small>per night</small></p>
    <a class="btn small" href="rooms.php">See all rooms</a>
  </article>
<?php endwhile; ?>
</div>
<?php include 'includes/footer.php'; ?>
