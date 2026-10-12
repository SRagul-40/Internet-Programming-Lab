</main>
<footer class="foot"><div class="wrap footgrid">
  <div><h4>S.S.R Hotel</h4><p>Clean rooms, good food and a warm welcome for every guest.</p></div>
  <div><h4>Contact</h4>
    <p>Phone: <?= e(HOTEL_PHONE) ?><br>Email: <?= e(HOTEL_EMAIL) ?></p></div>
  <div><h4>Address</h4><p><?= e(HOTEL_ADDRESS) ?></p></div>
</div>
<div class="wrap copy">&copy; <?= date('Y') ?> S.S.R Hotel. All rights reserved.</div></footer>
<script src="<?= $base ?? '' ?>js/script.js"></script>
</body></html>
