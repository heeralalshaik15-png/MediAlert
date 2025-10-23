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
.cb-feature-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
  justify-content: center;
  margin: 44px 0 38px 0;
}
.cb-feature-card2 {
  min-width: 0;
  border-radius: 18px;
  background: #fffdfa;
  box-shadow: 0 6px 24px #f8c27d40, 0 2px 6px #f0835233;
  padding: 34px 32px 28px 32px;
  max-width: 440px;
  flex-direction: column;
  align-items: start;
  transition: box-shadow .17s, transform .14s;
}
.cb-feature-card2:hover {
  box-shadow: 0 10px 38px #fb8d2765, 0 2px 18px #fec3800a;
  transform: translateY(-6px) scale(1.03);
}
.cb-feature-title {
  font-size: 1.34rem;
  font-weight: 700;
  margin-bottom: 6px;
  color: #ae4a0f;
}
.cb-feature-desc {
  color: #7c410f;
  font-size: 1em;
  opacity: 0.95;
}
@media (max-width: 1100px) {
  .cb-feature-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 800px) {
  .cb-feature-grid { grid-template-columns: 1fr; }
}

/* NEW PREMIUM TESTIMONIALS AND BENEFITS ONLY BELOW */
.premium-section-header {
  font-size: 2.4em;
  font-weight: 900;
  letter-spacing: 1.5px;
  color: #d2840c;
  margin-bottom: 0.7em;
  text-shadow: 0 2px 10px #fae6c1;
  font-family: 'Montserrat',sans-serif;
}
.cb-testimonials-row {
  display: flex;
  flex-wrap: wrap;
  gap: 40px 46px;
  justify-content: center;
  align-items: stretch;
  margin: 1.5em 0 2.5em 0;
}
.cb-testi-premium {
  background: rgba(255,255,255,0.88);
  box-shadow: 0 10px 38px #fae6c1cd, 0 1px 14px #ffd9a850;
  border-radius: 24px;
  padding: 42px 30px 28px 30px;
  max-width: fit-content;
  min-width: 0px;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.05em;
}
.cb-testi-avatar {
  width: 48px; height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #fdbe5e;
  margin-bottom: 10px;
  box-shadow:0 1px 9px #ffc98260;
  background: #fee2bb;
}
.cb-testi-quote {
  font-style: italic;
  color: #a85814;
  margin-bottom: 6px;
  font-size: 1.15em;
  text-align: center;
  line-height: 1.35;
  position: relative;
}
.cb-testi-quote:before {
  content: "“";
  font-size: 1.6em;
  color: #fdbe5e;
  vertical-align: top; margin-right: 0.09em;
}
.cb-testi-name {
  color: #e0992a;
  font-weight: bold;
  font-size: 1.04em;
  margin-bottom: -0.3em;
}
.cb-benefits-row-premium {
  display: flex;
  flex-wrap: wrap;
  gap: 38px;
  justify-content: center;
  align-items: flex-start;
  margin: 54px 0 42px 0;
}
.cb-benef-premium {
  background: rgba(255,255,255,0.98);
  box-shadow: 0 8px 40px #ffe3a230, 0 1.5px 8px #ffc77233;
  border-radius: 22px;
  padding: 38px 34px 32px 34px;
  max-width: 430px;
  min-width: 275px;
  flex: 1 1 350px;
  position: relative;
  border-left: 7px solid #fdbe5e;
  border-right: 4px solid #f8d08a;
  margin-bottom: 10px;
}
.cb-benef-title-premium {
  font-size: 1.51em;
  font-weight: 900;
  color: #c47a18;
  font-family: 'Montserrat',sans-serif;
  margin-bottom: 1em;
  letter-spacing: .01em;
  display: flex;
  align-items: center;
  gap: 9px;
  background: linear-gradient(90deg,#ffc46c,#f08724 80%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.cb-benef-list-premium {
  list-style-type: none;
  padding-left: 0;
  margin-bottom: 0;
}
.cb-benef-list-premium li {
  color: #a75f1a;
  margin-bottom: 15px;
  padding-left: 33px;
  position: relative;
  font-weight: 600;
  font-size: 1.11em;
  letter-spacing: .01em;
}
.cb-benef-list-premium li:before {
  content: "🌟";
  position: absolute;
  left: 0;
  font-size: 1.13em;
  top: 1px;
  color: #fdbe5e;
}
@media (max-width: 900px) {
  .cb-benefits-row-premium { flex-direction:column; align-items:center;}
  .cb-benef-premium {max-width: 490px;}
  .cb-testimonials-row { flex-direction:column; align-items:center;}
}

/*now */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;900&family=Roboto:ital,wght@0,400;0,600;1,400&display=swap');

.cb-about-card {
  background: rgba(255,255,255,0.46);
  box-shadow: 0 14px 38px #d8d2cb28, 0 2px 16px #dfcfd760;
  border-radius: 32px;
  padding: 44px 40px 34px 40px;
  max-width: 760px;
  backdrop-filter: blur(6px);
  margin: 70px auto 60px auto;
  border: 2.5px solid #f1e0da;
}



.cb-about-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 2.2em;
  font-weight: 900;
  background: linear-gradient(90deg, #f59a44 25%, #d07905 95%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  letter-spacing: 0.04em;
  margin-bottom: 22px;
  text-align: center;
  text-shadow: 0 2px 16px #ffe3c0;
}

.cb-about-desc {
  font-family: 'Roboto', sans-serif;
  color: #8e4602;
  font-size: 1.18em;
  letter-spacing: 0.009em;
  line-height: 1.57;
  text-align: center;
  margin-bottom: 0;
  font-style: italic;
  font-weight: 500;
}

.cb-about-email {
  color: #a85211;
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  text-decoration: none;
  border-bottom: 2px dashed #f59a44;
  transition: color .17s;
  font-style: normal;
}
.cb-about-email:hover {
  color: #f08724;
  border-bottom: 2px solid #ffaa55;
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

  <div class="cb-feature-grid">

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Medication Reminders</div>
    <div class="cb-feature-desc">Never miss a dose, get heartfelt reminders every day.</div>
  </div>

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Share With Loved Ones</div>
    <div class="cb-feature-desc">Your doctor and caretaker can help—always connected.</div>
  </div>

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Emergency Help</div>
    <div class="cb-feature-desc">Need support fast? One SOS click and you're never alone.</div>
  </div>

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Smart Appointments</div>
    <div class="cb-feature-desc">Set up, track, and get notified for your medical appointments right from your dashboard.</div>
  </div>

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Doctor Management</div>
    <div class="cb-feature-desc">Save your doctor details for quick access and easy sharing with caretakers or in emergencies.</div>
  </div>

  <div class="cb-feature-card2">
    <div class="cb-feature-title">Caretaker Dashboard</div>
    <div class="cb-feature-desc">Special dashboard for caretakers to monitor patient progress and receive alerts for missed doses.</div>
  </div>
</div>
</div>

<!-- PREMIUM TESTIMONIALS + BENEFITS -->
<div class="premium-section-header" style="text-align:center;">What Our Users Say</div>
<div class="cb-testimonials-row">
  <div class="cb-testi-premium">
    <div class="cb-testi-quote">
      CareBuddy keeps our family so much safer and organized. Daily reminders are a game-changer!
    </div>
    <div class="cb-testi-name">Mrs. Sharma, Patient</div>
  </div>
  <div class="cb-testi-premium">
    <div class="cb-testi-quote">
      Our clinic saves precious time with automatic alerts—Caretaker dashboard is amazing!
    </div>
    <div class="cb-testi-name">Dr. Singh, Physician</div>
  </div>
</div>
<hr>
<div class="premium-section-header" style="text-align:center;"><i>Benefits Given to our Users</i></div>
<div class="cb-benefits-row-premium">
  <div class="cb-benef-premium">
    <div class="cb-benef-title-premium">Benefits for Patients</div>
    <ul class="cb-benef-list-premium">
      <li>Automatic medication reminders</li>
      <li>Easy appointment tracking</li>
      <li>User-friendly dashboard</li>
      <li>Emergency SOS for help</li>
    </ul>
  </div>
  <div class="cb-benef-premium">
    <div class="cb-benef-title-premium">Benefits for Caretakers</div>
    <ul class="cb-benef-list-premium">
      <li>Monitor patient health progress</li>
      <li>Receive missed-dose alerts</li>
      <li>Direct alert giving to patients</li>
      <li>Save and manage doctor contacts</li>
    </ul>
  </div>
</div>

<!--now-->
<div class="cb-about-card">
  <div class="cb-about-title">
    About Us
  </div>
  <div class="cb-about-desc">
    CareBuddy is dedicated to helping families and caregivers create a safer, healthier world through simple technology.<br>
    Built by students and health professionals for real life, our mission is to make medication easier and support always available.<br>
    <br>
    Got suggestions, feedback, or want to join our mission? Reach us any time:<br>
    <a href="mailto:support@carebuddy.com" class="cb-about-email">support@carebuddy.com</a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
