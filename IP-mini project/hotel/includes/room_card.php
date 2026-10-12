<?php
// Renders one room card with 4 images (click a small image to enlarge it)
function room_card($r, $q, $opt = []) {
  $no = $r['room_no'];
  $badge = $opt['badge'] ?? '';
  $note  = $opt['note'] ?? '';
  $need  = $opt['need'] ?? 1;
  $can   = $opt['can_book'] ?? true;
  ?>
  <article class="card room">
    <div class="gallery">
      <img class="main" src="<?= e(img("rooms/{$no}_1")) ?>" alt="Room <?= e($no) ?> photo">
      <div class="thumbs">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <img src="<?= e(img("rooms/{$no}_$i")) ?>" alt="Room <?= e($no) ?> view <?= $i ?>" onclick="swapImg(this)">
      <?php endfor; ?>
      </div>
    </div>
    <?php if ($badge): ?><span class="badge"><?= e($badge) ?></span><?php endif; ?>
    <h3><?= e($r['type']) ?> · Room <?= e($no) ?></h3>
    <p><?= e($r['description']) ?></p>
    <p class="chips">
      <span><?= $r['ac'] ? 'AC' : 'Non-AC' ?></span>
      <span><?= $r['tv'] ? 'TV' : 'No TV' ?></span>
      <span><?= $r['balcony'] ? 'Balcony view' : 'No balcony' ?></span>
      <span>Sleeps <?= (int)$r['capacity'] ?></span>
    </p>
    <?php if ($note): ?><p class="warn"><?= $note ?></p><?php endif; ?>
    <p class="price">₹<?= number_format($r['price']) ?> <small>per night<?= $need > 1 ? " × $need rooms" : "" ?></small></p>
    <?php if ($can): ?>
      <a class="btn small" href="book.php?room=<?= (int)$r['id'] ?><?= $q ? '&' . $q : '' ?>">
        <?= $need > 1 ? "Book $need rooms" : "Book this room" ?></a>
    <?php endif; ?>
  </article>
<?php } ?>
