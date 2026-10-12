<?php $title = "Log in"; include 'includes/header.php'; $err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $st = $conn->prepare("SELECT * FROM users WHERE email=?"); $st->bind_param("s", $_POST['email']); $st->execute();
  $u = $st->get_result()->fetch_assoc();
  if ($u && password_verify($_POST['password'], $u['password'])) {
    $_SESSION['uid'] = $u['id']; $_SESSION['name'] = $u['name']; $_SESSION['role'] = $u['role'];
    header("Location: " . ($u['role']==='admin' ? "admin/index.php" : "rooms.php")); exit;
  } else { $err = "Email or password is incorrect."; }
} ?>
<form class="form" method="post">
  <h2>Log in</h2>
  <?php if (isset($_GET['registered'])): ?><p class="ok">Account created. Log in to continue.</p><?php endif; ?>
  <?php if ($err): ?><p class="error"><?= e($err) ?></p><?php endif; ?>
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button class="btn">Log in</button>
  <p class="note">No account? <a href="register.php">Create one</a></p>
</form>
<?php include 'includes/footer.php'; ?>
