<?php $title = "About S.S.R Hotel"; include 'includes/header.php'; $msg = ""; $err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['uid'])) {
  $rating = (int)$_POST['rating']; $comment = trim($_POST['comment']);
  if ($rating < 1 || $rating > 5 || $comment === '') { $err = "Please give a rating and a comment."; }
  else {
    $st = $conn->prepare("INSERT INTO reviews (user_id,name,rating,comment) VALUES (?,?,?,?)");
    $uid = $_SESSION['uid']; $nm = $_SESSION['name'];
    $st->bind_param("isis", $uid, $nm, $rating, $comment); $st->execute();
    $msg = "Thank you for your feedback!";
  }
}
$avg = $conn->query("SELECT COALESCE(AVG(rating),0) a, COUNT(*) c FROM reviews")->fetch_assoc();
$rev = $conn->query("SELECT * FROM reviews ORDER BY id DESC"); ?>
<h2>About S.S.R Hotel</h2>
<p class="lead">S.S.R Hotel has been welcoming families, business travellers and groups with simple, honest hospitality.
We believe a good stay starts with a clean room, a comfortable bed and staff who care.</p>

<div class="grid">
  <article class="card"><h3>Hotel quality</h3>
    <p>Every room has fresh linen, a clean attached bathroom and hot water all day. We offer AC and Non-AC rooms, TV, and balcony-view rooms.
    Our kitchen prepares fresh, hygienic, home-style meals for guests who choose the food option.</p></article>
  <article class="card"><h3>Maintenance</h3>
    <p>Rooms are cleaned daily and fully sanitised after every checkout. AC, plumbing, lights and TVs are inspected regularly,
    and any complaint is attended to quickly by our maintenance team. Power backup and drinking water are always available.</p></article>
  <article class="card"><h3>Service and safety</h3>
    <p>Our reception is open 24 hours. CCTV covers the entrance and corridors, and we help guests with booking more rooms
    when the group is bigger than one room can hold.</p></article>
</div>

<h2>Customer feedback</h2>
<p class="stats"><b><?= number_format($avg['a'], 1) ?> / 5</b> average rating from <b><?= (int)$avg['c'] ?></b> guest review<?= $avg['c']==1?'':'s' ?></p>
<?php if ($msg): ?><p class="ok"><?= e($msg) ?></p><?php endif; ?>
<?php if ($err): ?><p class="error"><?= e($err) ?></p><?php endif; ?>
<div class="grid">
<?php while ($r = $rev->fetch_assoc()): ?>
  <article class="card">
    <p class="stars" aria-label="<?= (int)$r['rating'] ?> out of 5"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></p>
    <p><?= e($r['comment']) ?></p>
    <p class="note">- <?= e($r['name']) ?></p>
  </article>
<?php endwhile; ?>
</div>

<?php if (isset($_SESSION['uid'])): ?>
<form class="form" method="post">
  <h3>Write your review</h3>
  <label>Rating
    <select name="rating"><option value="5">5 - Excellent</option><option value="4">4 - Good</option><option value="3">3 - Average</option><option value="2">2 - Poor</option><option value="1">1 - Bad</option></select></label>
  <label>Your feedback<textarea name="comment" rows="4" maxlength="500" required></textarea></label>
  <button class="btn">Submit review</button>
</form>
<?php else: ?><p class="note"><a href="login.php">Log in</a> to write a review.</p><?php endif; ?>
<?php include 'includes/footer.php'; ?>
