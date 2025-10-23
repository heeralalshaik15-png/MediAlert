<?php include 'includes/header.php'; ?>

<style>
body {
  background: linear-gradient(120deg, #ffe0c3 0%, #ffecd2 100%);
}
.profile-img {
  width: 170px; height:170px; object-fit:cover; border-radius:75px; box-shadow:0 8px 20px #f6b88f60;
  background: #ffe4bc;
  border: 5px solid #fff3e6;
}
.feature-card {
  border-radius: 22px;
  background: #fffdfa;
  box-shadow: 0 4px 22px #fcc6a268, 0 1.5px 1.5px #f3be9277;
  border: none;
  transition: transform .14s;
}
.feature-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 6px 34px #f3be9277;
}
.warm-btn-primary {
  background: linear-gradient(90deg, #f8c27d, #f08352 80%);
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 8px;
}
.warm-btn-primary:hover {
  background: linear-gradient(90deg, #ffd096, #fb8d27 90%);
  color: #fff;
}
</style>

<div class="container py-4">
  <div class="row align-items-center justify-content-center mb-4">
    <div class="col-lg-7 text-center">
      <h1 class="display-3 mb-2" style="font-weight:800; color:#d68828;">CAREBUDDY</h1>
      <h5 style="font-weight:600; color:#d16038;">Your warmest health companion</h5>
      <p class="lead" style="color:#6a380c;">
        Simple reminders, shared care, and instant emergency help—all at your fingertips.
      </p>
      <div class="my-3">
        <a href="register.php" class="btn warm-btn-primary btn-lg me-2 px-4 shadow-sm">Get Started</a>
        <a href="login.php" class="btn btn-outline-warning btn-lg px-4">Login</a>
      </div>
    </div>
    <div class="col-lg-5 text-center">
      <img src="MEDICAL_ICON_1024x1024@2x.webp" alt="health" class="profile-img mt-4 mt-lg-0">
    </div>
  </div>

  <div class="row gx-4 gy-3 justify-content-center mb-4">
    <div class="col-md-4">
      <div class="card feature-card h-100 text-center py-3 px-2">
        <div style="font-size:2.4rem; color:#ff914d;"><i class="bi bi-alarm"></i></div>
        <h5 class="fw-bold mt-2 mb-1" style="color:#ae4a0f;">Medication Reminders</h5>
        <span style="color:#943e10;">Never miss a dose, get heartfelt reminders every day.</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card h-100 text-center py-3 px-2">
        <div style="font-size:2.4rem; color:#fa9f7c;"><i class="bi bi-people"></i></div>
        <h5 class="fw-bold mt-2 mb-1" style="color:#ae4a0f;">Share With Loved Ones</h5>
        <span style="color:#943e10;">Your doctor and caretaker can help—always connected.</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card h-100 text-center py-3 px-2">
        <div style="font-size:2.4rem; color:#ff647e;"><i class="bi bi-exclamation-triangle"></i></div>
        <h5 class="fw-bold mt-2 mb-1" style="color:#ae4a0f;">Emergency Help</h5>
        <span style="color:#943e10;">Need support fast? One SOS click and you're never alone.</span>
      </div>
    </div>
  </div>

  <!-- Add more content/sections here. Scrolling will be enabled if it doesn't fit. -->
  <div class="row justify-content-center">
    <div class="col-lg-10 text-center mt-5 pb-4">
      <h4 style="color:#ca820a;">More than reminders — we're your partner in care.</h4>
      <p class="fs-5" style="color:#bf5b2c;">
        Experience peace of mind for your whole family with CAREBUDDY’s modern, supportive, and beautiful dashboard. Start your healthy journey today!
      </p>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
