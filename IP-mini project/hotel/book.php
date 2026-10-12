<?php require_once 'config.php'; require_login(); include_once 'includes/room_card.php';
$rid = (int)($_GET['room'] ?? $_POST['room'] ?? 0);
$st = $conn->prepare("SELECT * FROM rooms WHERE id=?"); $st->bind_param("i", $rid); $st->execute();
$room = $st->get_result()->fetch_assoc(); if (!$room) { header("Location: rooms.php"); exit; }
$err = "";
$vIn = $_POST['check_in'] ?? $_GET['check_in'] ?? '';
$vOut = $_POST['check_out'] ?? $_GET['check_out'] ?? '';
$vG = (int)($_POST['guests'] ?? $_GET['guests'] ?? 2);
$vFood = (int)($_POST['food'] ?? $_GET['food'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $in = $_POST['check_in']; $out = $_POST['check_out']; $g = max(1, (int)$_POST['guests']);
  $food = $vFood ? 1 : 0;
  $need = (int)ceil($g / $room['capacity']);           // rooms needed for this many members
  if ($in < date('Y-m-d')) $err = "Check-in cannot be in the past.";
  elseif ($out <= $in) $err = "Check-out must be after check-in.";
  else {
    // free rooms of the same kind (selected room first)
    $st = $conn->prepare("SELECT * FROM rooms WHERE type=? AND capacity=? AND ac=? AND id NOT IN
      (SELECT room_id FROM bookings WHERE status='Confirmed' AND check_in < ? AND check_out > ?)
      ORDER BY (id=?) DESC, room_no LIMIT ?");
    $st->bind_param("siissii", $room['type'], $room['capacity'], $room['ac'], $out, $in, $rid, $need);
    $st->execute(); $free = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    if (count($free) < $need || $free[0]['id'] != $rid) {
      $err = count($free) < $need
        ? "You need $need rooms for $g members but only " . count($free) . " are free for those dates."
        : "This room is already booked for those dates.";
    } else {
      $nights = (new DateTime($in))->diff(new DateTime($out))->days;
      $ref = strtoupper(substr(uniqid(), -8));
      $per = intdiv($g, $need); $rem = $g % $need;
      $ins = $conn->prepare("INSERT INTO bookings (booking_ref,user_id,room_id,check_in,check_out,guests,food,total) VALUES (?,?,?,?,?,?,?,?)");
      foreach ($free as $i => $r) {
        $gi = $per + ($i < $rem ? 1 : 0);
        $total = $nights * $r['price'] + ($food ? $nights * $gi * FOOD_PRICE : 0);
        $uid = $_SESSION['uid'];
        $ins->bind_param("siissiid", $ref, $uid, $r['id'], $in, $out, $gi, $food, $total);
        $ins->execute();
      }
      header("Location: my_bookings.php?booked=" . $need); exit;
    }
  }
}
$title = "Book room"; include 'includes/header.php'; ?>
<div class="bookwrap">
  <div>
    <?php room_card($room, '', ['can_book' => false]); ?>
  </div>
  <form class="form" method="post" id="bookform"
        data-price="<?= $room['price'] ?>" data-cap="<?= (int)$room['capacity'] ?>" data-food="<?= FOOD_PRICE ?>">
    <h2>Book <?= e($room['type']) ?> · Room <?= e($room['room_no']) ?></h2>
    <p>₹<?= number_format($room['price']) ?> per night · sleeps <?= (int)$room['capacity'] ?> only</p>
    <?php if ($err): ?><p class="error"><?= e($err) ?></p><?php endif; ?>
    <input type="hidden" name="room" value="<?= $rid ?>">
    <label>Check-in<input type="date" id="in" name="check_in" value="<?= e($vIn) ?>" required></label>
    <label>Check-out<input type="date" id="out" name="check_out" value="<?= e($vOut) ?>" required></label>
    <label>Total members<input type="number" id="guests" name="guests" min="1" max="20" value="<?= $vG ?>" required></label>
    <label>Food needed?
      <select name="food" id="food">
        <option value="0" <?= !$vFood?'selected':'' ?>>No food</option>
        <option value="1" <?= $vFood?'selected':'' ?>>Yes (₹<?= FOOD_PRICE ?> per person per night)</option>
      </select></label>
    <p class="warn" id="roomsmsg"></p>
    <p class="total" id="total"></p>
    <button class="btn">Confirm booking</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
