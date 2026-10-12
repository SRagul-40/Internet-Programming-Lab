<?php $title = "My bookings"; include 'includes/header.php'; require_login();
if (isset($_GET['cancel'])) {   // cancels every room in that booking
  $st = $conn->prepare("UPDATE bookings SET status='Cancelled' WHERE booking_ref=? AND user_id=?");
  $st->bind_param("si", $_GET['cancel'], $_SESSION['uid']); $st->execute();
}
$st = $conn->prepare("SELECT b.*, r.room_no, r.type, r.ac FROM bookings b JOIN rooms r ON r.id=b.room_id WHERE b.user_id=? ORDER BY b.id DESC");
$st->bind_param("i", $_SESSION['uid']); $st->execute(); $res = $st->get_result(); ?>
<h2>My bookings</h2>
<?php if (isset($_GET['booked'])): ?><p class="ok">Booking confirmed - <?= (int)$_GET['booked'] ?> room<?= (int)$_GET['booked']>1?'s':'' ?> booked.</p><?php endif; ?>
<?php if ($res->num_rows === 0): ?><p class="note">You have no bookings yet. <a href="rooms.php">Browse rooms</a></p><?php else: ?>
<div class="scroll"><table>
<tr><th>Ref</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Members</th><th>AC</th><th>Food</th><th>Total</th><th>Status</th><th></th></tr>
<?php while ($b = $res->fetch_assoc()): ?>
<tr><td><?= e($b['booking_ref']) ?></td><td><?= e($b['type']) ?> <?= e($b['room_no']) ?></td><td><?= $b['check_in'] ?></td><td><?= $b['check_out'] ?></td>
<td><?= $b['guests'] ?></td><td><?= $b['ac'] ? 'AC' : 'Non-AC' ?></td><td><?= $b['food'] ? 'Yes' : 'No' ?></td><td>₹<?= number_format($b['total']) ?></td><td><?= $b['status'] ?></td>
<td><?php if ($b['status']==='Confirmed'): ?><a href="my_bookings.php?cancel=<?= e($b['booking_ref']) ?>" onclick="return confirm('Cancel this booking (all its rooms)?')">Cancel booking</a><?php endif; ?></td></tr>
<?php endwhile; ?></table></div><?php endif; ?>
<?php include 'includes/footer.php'; ?>
