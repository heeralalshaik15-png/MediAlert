<?php
include 'includes/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'caretaker'){
  header("Location: login.php"); exit;
}

$uid = $_SESSION['user']['user_id'];

if (isset($_POST['send_alert']) && !empty($_POST['alert_message']) && !empty($_POST['patient_alert_id'])) {
  $msg = trim($_POST['alert_message']);
  $to_patient = intval($_POST['patient_alert_id']);
  $stmt = $pdo->prepare("INSERT INTO alerts (user_id, caretaker_id, type, message) VALUES (?, ?, 'custom', ?)");
  $stmt->execute([$to_patient, $uid, $msg]);
}

// Handle linking EXISTING patient
if(isset($_POST['link_patient_btn'])){
  $patient_email = $_POST['link_patient_email'];
  $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND role = 'patient'");
  $stmt->execute([$patient_email]);
  $p = $stmt->fetch();
  if($p){
    $pid = $p['user_id'];
    $pdo->prepare("UPDATE users SET linked_user = ? WHERE user_id = ?")->execute([$uid, $pid]);
    header("Location: caretaker_dashboard.php?link=success");
    exit;
  } else {
    $link_error = "No patient with that email found.";
  }
}

// Handle ADD new patient from modal
if(isset($_POST['add_patient_btn'])){
  $name = $_POST['add_patient_name'];
  $email = $_POST['add_patient_email'];
  $password = password_hash($_POST['add_patient_password'], PASSWORD_DEFAULT);
  $role = 'patient';
  $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  if($stmt->fetchAll()){
    $add_error = "Email already in use.";
  } else {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, linked_user) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role, $uid]);
    header("Location: caretaker_dashboard.php?add=success");
    exit;
  }
}

// Fetch all patients linked to this caretaker
$stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE linked_user = ?");
$stmt->execute([$uid]);
$patients = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT * FROM alerts WHERE caretaker_id = ? ORDER BY created_at DESC");
$stmt->execute([$uid]);
$alerts = $stmt->fetchAll();

?>

<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Caretaker: <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h3>
  <!-- Add/Link Patient Button -->
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add/Link Patient</button>
</div>


<!-- Add/Link Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPatientModalLabel">Add or Link Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Link Existing Patient -->
        <form method="post" class="mb-4">
          <h6>Link Existing Patient (by Email):</h6>
          <input type="email" class="form-control mb-2" name="link_patient_email" placeholder="Patient Email" required>
          <button type="submit" name="link_patient_btn" class="btn btn-outline-primary w-100">Link Patient</button>
          <?php if(!empty($link_error)) {?>
            <div class="text-danger mt-1"><?php echo $link_error; ?></div>
          <?php }?>
        </form>
        <hr>
        <!-- Add New Patient -->
        <form method="post">
          <h6>Add New Patient:</h6>
          <input type="text" class="form-control mb-2" name="add_patient_name" placeholder="Patient Name" required>
          <input type="email" class="form-control mb-2" name="add_patient_email" placeholder="Patient Email" required>
          <input type="password" class="form-control mb-2" name="add_patient_password" placeholder="Password" required>
          <button type="submit" name="add_patient_btn" class="btn btn-primary w-100">Add Patient</button>
          <?php if(!empty($add_error)) {?>
            <div class="text-danger mt-1"><?php echo $add_error; ?></div>
          <?php }?>
        </form>
      </div>
    </div>
  </div>
</div>

<?php if(!$patients): ?>
  <div class="alert alert-warning">No patients linked. Please add or link a patient.</div>
<?php else: ?>
  <?php if($patients): ?>
  <div class="mb-4">
    <strong>Select a patient:</strong>
    <?php foreach($patients as $p): ?>
      <a href="?patient_id=<?php echo $p['user_id']; ?>" class="btn btn-outline-primary btn-sm <?php if(isset($_GET['patient_id']) && $_GET['patient_id']==$p['user_id']) echo 'active'; ?>">
        <?php echo htmlspecialchars($p['name']); ?>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Appointment Button -->
<button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#appointmentModal">Set Appointment</button>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="add_appointment.php">
      <div class="modal-header">
        <h5 class="modal-title" id="appointmentModalLabel">Set Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label>Patient:</label>
        <select name="patient_id" class="form-control mb-2" required>
          <option value="">Select patient</option>
          <?php foreach($patients as $p): ?>
            <option value="<?php echo $p['user_id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <label>Date:</label>
        <input type="date" name="date" class="form-control mb-2" required>
        <label>Time:</label>
        <input type="time" name="time" class="form-control mb-2" required>
        <label>Details:</label>
        <input type="text" name="desc" class="form-control" placeholder="e.g. doctor visit" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Save</button>
      </div>
    </form>
  </div>
</div>
<?php
$show_patients = $patients;
if (isset($_GET['patient_id'])) {
  $show_patients = array_filter($patients, function($p) {
    return $p['user_id'] == $_GET['patient_id'];
  });
}

foreach($show_patients as $patient):
  $patientId = $patient['user_id'];
  $stmt = $pdo->prepare("SELECT m.*, u.name as added_by_name FROM medications m LEFT JOIN users u ON m.added_by=u.user_id WHERE m.user_id=? ORDER BY m.time");
  $stmt->execute([$patientId]);
  $medications = $stmt->fetchAll();
  $stmt = $pdo->prepare("SELECT * FROM doctors WHERE user_id = ?");
  $stmt->execute([$patientId]);
  $doctors = $stmt->fetchAll();
?>
  <div class="row mb-5">
    <div class="col-lg-8">
      <!-- Send Custom Alert to Patient -->
<form method="post" style="margin-bottom:1rem;">
  <input type="hidden" name="patient_alert_id" value="<?php echo $patientId; ?>">
  <div class="input-group">
    <input type="text" name="alert_message" class="form-control" placeholder="Write alert message to patient..." required>
    <button type="submit" name="send_alert" class="btn btn-warning">Send Alert</button>
  </div>
</form>

      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong><?php echo htmlspecialchars($patient['name']); ?>'s Medications</strong>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMedModal_<?php echo $patientId; ?>">Add Medicine</button>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead class="table-light"><tr><th>Name</th><th>Dosage</th><th>Time</th><th>Added By</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                <?php if(!$medications): ?>
                  <tr><td colspan="6" class="text-center p-3">No medications.</td></tr>
                <?php else: foreach($medications as $m): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($m['name']); ?></td>
                    <td><?php echo htmlspecialchars($m['dosage']); ?></td>
                    <td><?php echo htmlspecialchars(substr($m['time'],0,5)); ?></td>
                    <td><?php echo htmlspecialchars($m['added_by_name'] ?? 'Self'); ?></td>
                    <td><span class="badge <?php echo $m['status']=='taken' ? 'bg-success' : ($m['status']=='missed' ? 'bg-danger' : 'bg-secondary'); ?>"><?php echo $m['status']; ?></span></td>
                    <td>
                      <a href="delete_med.php?med_id=<?php echo $m['med_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                  </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Medicine Modal for this patient -->
<div class="modal fade" id="addMedModal_<?php echo $patientId; ?>" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="add_med.php">
      <input type="hidden" name="user_id" value="<?php echo $patientId; ?>">
      <div class="modal-header">
        <h5 class="modal-title">Add Medicine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <input class="form-control" name="name" placeholder="Medicine name" required>
        </div>
        <div class="mb-2">
          <input class="form-control" name="dosage" placeholder="Dosage (e.g. 1 tablet)">
        </div>
        <div class="mb-2">
          <label>Time</label>
          <input type="time" class="form-control" name="time" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Add</button>
      </div>
    </form>
  </div>
</div>

    <div class="col-lg-4">
      <div class="card mb-3">
        <div class="card-header"><strong>Doctor Contacts</strong></div>
        <div class="card-body">
          <form method="post" action="add_doctor.php">
            <input type="hidden" name="user_id" value="<?php echo $patientId; ?>">
            <div class="mb-2"><input class="form-control" name="name" placeholder="Doctor name" required></div>
            <div class="mb-2"><input class="form-control" name="specialization" placeholder="Specialization"></div>
            <div class="mb-2"><input class="form-control" name="contact" placeholder="Phone number" required></div>
            <button class="btn btn-sm btn-primary w-100">Add Doctor</button>
          </form>
          <hr>
          <?php if(!$doctors): ?><p class="text-muted">No doctors added.</p><?php else: ?>
            <ul class="list-group">
              <?php foreach($doctors as $d): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-semibold"><?php echo htmlspecialchars($d['name']); ?></div>
                    <small class="text-muted"><?php echo htmlspecialchars($d['specialization']); ?></small>
                  </div>
                  <div><a href="tel:<?php echo htmlspecialchars($d['contact']); ?>" class="btn btn-sm btn-outline-primary">Call</a></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
     <div class="card">
  <div class="card-header"><strong>Alerts</strong></div>
  <div class="card-body" id="alertsArea_<?php echo $patientId; ?>">
    <ul class="list-group list-group-flush">
      <?php
      $found=false;
      if ($alerts) {
        foreach ($alerts as $alert) {
          // Only show missed alerts for maximum clarity
          if ($alert['type'] == 'missed' && $alert['user_id'] == $patientId) {
            $found = true;
            echo '<li class="list-group-item bg-danger text-white">';
            echo '<strong>' . htmlspecialchars($alert['message']) . '</strong>';
            echo '<br><span class="small text-light">' . htmlspecialchars($alert['created_at']) . '</span>';
            echo '</li>';
          }
        }
      } if(!$found) {
        echo '<li class="list-group-item text-muted">No missed alerts yet.</li>';
      }
      ?>
    </ul>
  </div>
</div>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<script>
// Optionally you can use AJAX to load individual alerts per patient here if desired
</script>
<?php include 'includes/footer.php'; ?>
