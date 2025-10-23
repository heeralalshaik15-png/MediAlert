<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediAlert</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet"/>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, #ffd19d, #ff9f5e 90%); box-shadow: 0 2px 12px #ffd19d44;">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="index.php"> MediAlert</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $_SESSION['user']['role']=='patient' ? 'patient_dashboard.php':'caretaker_dashboard.php'; ?>">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
        <li class="nav-item ms-3">
        <button id="theme-toggle" class="btn btn-light btn-sm">🌙</button>
        </li>

      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid my-4 px-4">
