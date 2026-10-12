<?php $base = "../"; $title = "Admin"; include '../includes/header.php'; require_admin();
if (isset($_POST['add_room'])) {
  $ac = ($_POST['ac'] ?? 'ac') === 'ac' ? 1 : 0; $tv = isset($_POST['tv']) ? 1 : 0; $bal = isset($_POST['balcony']) ? 1 : 0;
  $st = $conn->prepare("INSERT INTO rooms (room_no,type,price,capacity,ac,tv,balcony,description) VALUES (?,?,?,?,?,?,?,?)");
  $st->bind_param("ssdiiiis", $_POST['room_no'], $_POST['type'], $_POST['price'], $_POST['capacity'], $ac, $tv, $bal, $_POST['description']); $st->execute();
}
if (isset($_GET['delete_room'])) { $st = $conn->prepare("DELETE FROM rooms WHERE id=?"); $st->bind_param("i", $_GET['delete_room']); $st->execute(); }
if (isset($_GET['cancel'])) { $st = $conn->prepare("UPDATE bookings SET status='Cancelled' WHERE booking_ref=?"); $st->bind_param("s", $_GET['cancel']); $st->execute(); }
$stats = $conn->query("SELECT (SELECT COUNT(*) FROM rooms) rooms, (SELECT COUNT(*) FROM users WHERE role='guest') guests,
  (SELECT COUNT(*) FROM bookings WHERE status='Confirmed') bookings,
  (SELECT COALESCE(SUM(total),0) FROM bookings WHERE status='Confirmed') revenue")->fetch_assoc();
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_no");
$bk = $conn->query("SELECT b.*, u.name, u.phone, r.room_no FROM bookings b JOIN users u ON u.id=b.user_id JOIN rooms r ON r.id=b.room_id ORDER BY b.id DESC"); ?>
<h2>Admin dashboard</h2>
<p class="stats"><b><?= $stats['rooms'] ?></b> rooms · <b><?= $stats['guests'] ?></b> guests · <b><?= $stats['bookings'] ?></b> active bookings · <b>₹<?= number_format($stats['revenue']) ?></b> booked revenue</p>

<h3>All bookings</h3>
<div class="scroll"><table>
<tr><th>Ref</th><th>Guest</th><th>Phone</th><th>Room</th><th>Dates</th><th>Members</th><th>Food</th><th>Total</th><th>Status</th><th></th></tr>
<?php while ($b = $bk->fetch_assoc()): ?>
<tr><td><?= e($b['booking_ref']) ?></td><td><?= e($b['name']) ?></td><td><?= e($b['phone']) ?></td><td><?= e($b['room_no']) ?></td>
<td><?= $b['check_in'] ?> to <?= $b['check_out'] ?></td><td><?= $b['guests'] ?></td><td><?= $b['food'] ? 'Yes' : 'No' ?></td><td>₹<?= number_format($b['total']) ?></td><td><?= $b['status'] ?></td>
<td><?php if ($b['status']==='Confirmed'): ?><a href="?cancel=<?= e($b['booking_ref']) ?>" onclick="return confirm('Cancel this booking?')">Cancel booking</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div>

<h3>Manage rooms</h3>
<div class="scroll"><table>
<tr><th>Room</th><th>Type</th><th>Price</th><th>Sleeps</th><th>AC</th><th>TV</th><th>Balcony</th><th></th></tr>
<?php while ($r = $rooms->fetch_assoc()): ?>
<tr><td><?= e($r['room_no']) ?></td><td><?= e($r['type']) ?></td><td>₹<?= number_format($r['price']) ?></td><td><?= $r['capacity'] ?></td><td><?= $r['ac']?'AC':'Non-AC' ?></td><td><?= $r['tv']?'Yes':'No' ?></td><td><?= $r['balcony']?'Yes':'No' ?></td>
<td><a href="?delete_room=<?= $r['id'] ?>" onclick="return confirm('Delete this room and its bookings?')">Delete room</a></td></tr>
<?php endwhile; ?></table></div>

<form class="form" method="post">
  <h3>Add a room</h3>
  <label>Room number<input name="room_no" required></label>
  <label>Type<input name="type" required></label>
  <label>Price per night (₹)<input type="number" name="price" min="1" required></label>
  <label>Capacity<input type="number" name="capacity" min="1" max="10" required></label>
  <label>AC type<select name="ac"><option value="ac">AC</option><option value="non">Non-AC</option></select></label>
  <label class="check"><input type="checkbox" name="tv" checked> TV</label>
  <label class="check"><input type="checkbox" name="balcony"> Balcony view</label>
  <label>Description<input name="description"></label>
  <button class="btn" name="add_room">Add room</button>
</form>
<?php include '../includes/footer.php'; ?>
