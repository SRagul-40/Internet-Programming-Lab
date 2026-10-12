<?php $title = "Rooms"; include 'includes/header.php'; include 'includes/room_card.php';
$in  = $_GET['check_in'] ?? '';  $out = $_GET['check_out'] ?? '';
$g   = max(1, (int)($_GET['guests'] ?? 2));
$food = (int)($_GET['food'] ?? 0) ? 1 : 0;
$ac  = in_array($_GET['ac'] ?? 'any', ['ac','non']) ? $_GET['ac'] : 'any';
$tv  = isset($_GET['tv']) ? 1 : 0;
$bal = isset($_GET['balcony']) ? 1 : 0;
$searched = ($in && $out);
$err = "";
if ($searched) {
  if ($in < date('Y-m-d')) { $err = "Check-in cannot be in the past."; $searched = false; }
  elseif ($out <= $in)     { $err = "Check-out must be after check-in."; $searched = false; }
}
$nights = $searched ? (new DateTime($in))->diff(new DateTime($out))->days : 0;

$fit = []; $multi = [];
if ($searched) {
  $sql = "SELECT * FROM rooms WHERE id NOT IN
          (SELECT room_id FROM bookings WHERE status='Confirmed' AND check_in < ? AND check_out > ?)";
  if ($ac === 'ac')  $sql .= " AND ac=1";
  if ($ac === 'non') $sql .= " AND ac=0";
  if ($tv)  $sql .= " AND tv=1";
  if ($bal) $sql .= " AND balcony=1";
  $sql .= " ORDER BY price";
  $st = $conn->prepare($sql); $st->bind_param("ss", $out, $in); $st->execute();
  $all = $st->get_result()->fetch_all(MYSQLI_ASSOC);
  foreach ($all as $r) { if ($r['capacity'] >= $g) $fit[] = $r; else $multi[] = $r; }
  // best fit = smallest room that holds everyone (least wasted beds), cheaper first
  usort($fit, fn($a, $b) => [$a['capacity'], $a['price']] <=> [$b['capacity'], $b['price']]);
}
$q = $searched ? http_build_query(['check_in'=>$in,'check_out'=>$out,'guests'=>$g,'food'=>$food]) : "";
?>
<h2>Find your room</h2>
<form class="search big" method="get">
  <label>Check-in<input type="date" name="check_in" value="<?= e($in) ?>" required></label>
  <label>Check-out<input type="date" name="check_out" value="<?= e($out) ?>" required></label>
  <label>How many members?<input type="number" name="guests" min="1" max="20" value="<?= $g ?>" required></label>
  <label>Food needed?
    <select name="food"><option value="0" <?= !$food ? 'selected' : '' ?>>No food</option>
    <option value="1" <?= $food ? 'selected' : '' ?>>Yes, with food (₹<?= FOOD_PRICE ?>/person/night)</option></select></label>
  <label>AC or Non-AC?
    <select name="ac"><option value="any" <?= $ac==='any'?'selected':'' ?>>Any</option>
    <option value="ac" <?= $ac==='ac'?'selected':'' ?>>AC</option>
    <option value="non" <?= $ac==='non'?'selected':'' ?>>Non-AC</option></select></label>
  <label class="check"><input type="checkbox" name="tv" <?= $tv?'checked':'' ?>> TV needed</label>
  <label class="check"><input type="checkbox" name="balcony" <?= $bal?'checked':'' ?>> Balcony view room</label>
  <button class="btn">Suggest rooms</button>
</form>
<?php if ($err): ?><p class="error"><?= e($err) ?></p><?php endif; ?>

<?php if (!$searched): ?>
  <p class="note">Choose your check-in and check-out dates and the number of members. We will suggest the best rooms for your group.</p>
<?php else: ?>
  <p class="stats">Showing rooms free from <b><?= e($in) ?></b> to <b><?= e($out) ?></b> (<?= $nights ?> night<?= $nights>1?'s':'' ?>) for <b><?= $g ?></b> member<?= $g>1?'s':'' ?>
    · <?= $ac==='any' ? 'AC / Non-AC' : ($ac==='ac' ? 'AC' : 'Non-AC') ?><?= $tv ? ' · TV' : '' ?><?= $bal ? ' · Balcony view' : '' ?><?= $food ? ' · With food' : ' · No food' ?></p>

  <h3>Best rooms for <?= $g ?> member<?= $g>1?'s':'' ?></h3>
  <div class="grid">
  <?php if (!$fit): ?><p class="note">No single room sleeps <?= $g ?> members with your choices. See the multi-room options below.</p><?php endif; ?>
  <?php foreach ($fit as $i => $r):
    $extra = $r['capacity'] - $g;
    $note = $extra > 0 ? "Sleeps {$r['capacity']} - fits your {$g} member" . ($g>1?'s':'') . " comfortably." : "Perfect fit for your group.";
    room_card($r, $q, ['badge' => $i === 0 ? 'Best suggestion' : '', 'note' => $note]);
  endforeach; ?>
  </div>

  <?php if ($multi): ?>
  <h3>Smaller rooms - book more than one room</h3>
  <p class="note">These rooms sleep fewer people than your group, so you need to book more than one room of the same type.</p>
  <div class="grid">
  <?php foreach ($multi as $r):
    $need = (int)ceil($g / $r['capacity']);
    $same = 0;
    foreach ($multi as $m) { if ($m['type'] === $r['type'] && $m['capacity'] == $r['capacity'] && $m['ac'] == $r['ac']) $same++; }
    $ok = $same >= $need;
    $note = "This room sleeps only {$r['capacity']} person" . ($r['capacity']>1?'s':'') . ". For {$g} members you need to book <b>$need rooms</b>.";
    if (!$ok) $note .= " Only $same such room" . ($same>1?'s are':' is') . " free for these dates.";
    room_card($r, $q, ['note' => $note, 'need' => $need, 'can_book' => $ok]);
  endforeach; ?>
  </div>
  <?php endif; ?>
  <?php if (!$fit && !$multi): ?><p class="note">No rooms are free for those dates and choices. Try different dates or relax the AC / TV / balcony filters.</p><?php endif; ?>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
