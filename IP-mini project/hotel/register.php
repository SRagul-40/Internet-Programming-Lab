<?php $title = "Create account"; include 'includes/header.php'; $err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']); $email = trim($_POST['email']); $phone = trim($_POST['phone']); $pw = $_POST['password'];
  if (strlen($pw) < 6) { $err = "Password must be at least 6 characters."; }
  else {
    $st = $conn->prepare("SELECT id FROM users WHERE email=?"); $st->bind_param("s", $email); $st->execute();
    if ($st->get_result()->num_rows) { $err = "This email is already registered. Log in instead."; }
    else {
      $h = password_hash($pw, PASSWORD_DEFAULT);
      $st = $conn->prepare("INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
      $st->bind_param("ssss", $name, $email, $phone, $h); $st->execute();
      header("Location: login.php?registered=1"); exit;
    }
  }
} ?>
<form class="form" method="post" onsubmit="return validateRegister(this)">
  <h2>Create account</h2>
  <?php if ($err): ?><p class="error"><?= e($err) ?></p><?php endif; ?>
  <label>Full name<input name="name" required></label>
  <label>Email<input type="email" name="email" required></label>
  <label>Phone<input name="phone" pattern="[0-9]{10}" title="10 digit number" required></label>
  <label>Password<input type="password" name="password" required></label>
  <label>Confirm password<input type="password" name="confirm" required></label>
  <button class="btn">Create account</button>
</form>
<?php include 'includes/footer.php'; ?>
