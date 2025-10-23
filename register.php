<?php
include 'includes/db.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $linked = !empty($_POST['linked_user']) ? intval($_POST['linked_user']) : null;

    if(!$name || !$email || !$password) $errors[] = "All fields are required.";

    if(empty($errors)){
        // check existing
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role,linked_user) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name,$email,$hash,$role,$linked]);
            header("Location: login.php?registered=1");
            exit;
        }
    }
}

// fetch patients for caretaker linking
$patients = [];
$stmt = $pdo->query("SELECT user_id,name,email FROM users WHERE role='patient' ORDER BY name");
$patients = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title mb-3">Register</h4>
        <?php if($errors): ?>
          <div class="alert alert-danger">
            <?php foreach($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
          </div>
        <?php endif; ?>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Full name</label>
            <input class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="roleSelect" required>
              <option value="patient">Patient</option>
              <option value="caretaker">Caretaker</option>
            </select>
          </div>
          <div class="mb-3" id="linkRow" style="display:none;">
            <label class="form-label">Link to patient (choose patient account)</label>
            <select class="form-select" name="linked_user">
              <option value="">-- select patient --</option>
              <?php foreach($patients as $p): ?>
                <option value="<?php echo $p['user_id']; ?>"><?php echo htmlspecialchars($p['name'].' ('.$p['email'].')'); ?></option>
              <?php endforeach; ?>
            </select>
            <small class="text-muted">If you are caretaker, link to the patient here.</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button class="btn btn-primary">Register</button>
          <a href="login.php" class="btn btn-link">Already have an account?</a>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('roleSelect').addEventListener('change', function(){
  document.getElementById('linkRow').style.display = this.value === 'caretaker' ? 'block' : 'none';
});
</script>
<?php include 'includes/footer.php'; ?>
