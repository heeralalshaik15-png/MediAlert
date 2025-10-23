<?php
include 'includes/db.php';
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($pass, $user['password'])){
        // set session user (only needed fields)
        $_SESSION['user'] = [
          'user_id' => $user['user_id'],
          'name' => $user['name'],
          'email' => $user['email'],
          'role' => $user['role'],
          'linked_user' => $user['linked_user']
        ];
        // redirect
        if($user['role'] === 'patient') header("Location: patient_dashboard.php");
        else header("Location: caretaker_dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title">Login</h4>
        <?php if(isset($_GET['registered'])): ?>
          <div class="alert alert-success">Registered successfully. Please login.</div>
        <?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button class="btn btn-primary">Login</button>
          <a href="register.php" class="btn btn-link">Register</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
