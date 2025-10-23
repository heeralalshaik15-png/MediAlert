<?php
include 'includes/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient'){
  header("Location: login.php"); exit;
}
$uid = $_SESSION['user']['user_id'];


// --- Automatic missed med notification ---
function checkAndAlertMissedMeds($pdo, $uid) {
  // Get patient's caretaker
  $stmt = $pdo->prepare("SELECT linked_user FROM users WHERE user_id = ?");
  $stmt->execute([$uid]);
  $caretaker_id = $stmt->fetchColumn();
  if(!$caretaker_id){ return; }


  $today = date('Y-m-d');
  $now = date('H:i:s');
  $stmt = $pdo->prepare("SELECT * FROM medications WHERE user_id = ? AND status = 'pending'");
  $stmt->execute([$uid]);
  $meds = $stmt->fetchAll();
  foreach($meds as $med) {
  // Add 10 minutes to scheduled time
  $med_time_plus_10 = date('H:i:s', strtotime($med['time'].'+10 minutes'));
  $now_time = date('H:i:s');
  echo "Now: $now_time, Med: {$med['time']}, Med+10: $med_time_plus_10<br>";

  // Only run on today's meds, since you store just TIME
  if($now_time > $med_time_plus_10) {
    // Check for duplicate missed alert
    $check = $pdo->prepare("SELECT 1 FROM alerts WHERE type='missed' AND message LIKE ? AND user_id=? AND caretaker_id=? AND DATE(created_at)=?");
    $pattern = "%".$med['name']."%".date('Y-m-d')."%";
    $check->execute([$pattern, $uid, $caretaker_id, date('Y-m-d')]);
    if(!$check->fetch()) {
      $msg = "Medicine missed: ".$med['name']." (".$med['dosage'].") at ".substr($med['time'],0,5);
  // 1. Alert for caretaker (same as before)
      $ins = $pdo->prepare("INSERT INTO alerts (user_id, caretaker_id, type, message) VALUES (?, ?, 'missed', ?)");
      $ins->execute([$uid, $caretaker_id, $msg]);
  // 2. Alert for patient (caretaker_id NULL or 0)
      $ins2 = $pdo->prepare("INSERT INTO alerts (user_id, caretaker_id, type, message) VALUES (?, ?, 'missed', ?)");
      $ins2->execute([$uid, $msg]);  // use 0 for caretaker_id; or you can use NULL if your DB allows it
      $pdo->prepare("UPDATE medications SET status='missed' WHERE med_id=?")->execute([$med['med_id']]);
}

  }
}

}
checkAndAlertMissedMeds($pdo, $uid);


// --- End missed alert logic ---


// fetch meds
$stmt = $pdo->prepare("SELECT m.*, u.name AS added_by_name FROM medications m LEFT JOIN users u ON m.added_by = u.user_id WHERE m.user_id = ? ORDER BY m.time");
$stmt->execute([$uid]);
$medications = $stmt->fetchAll();


// fetch doctors
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$uid]);
$doctors = $stmt->fetchAll();
?>


<?php include 'includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h3>
  <div>
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#sosModal">Trigger SOS</button>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Medications</strong>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMedModal">Add Medicine</button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Dosage</th><th>Time</th><th>Added By</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              <?php if(!$medications): ?>
                <tr><td colspan="6" class="text-center p-3">No medications added.</td></tr>
              <?php else: foreach($medications as $m): ?>
                <tr>
                  <td><?php echo htmlspecialchars($m['name']); ?></td>
                  <td><?php echo htmlspecialchars($m['dosage']); ?></td>
                  <td><?php echo htmlspecialchars(substr($m['time'],0,5)); ?></td>
                  <td><?php echo htmlspecialchars($m['added_by_name'] ?? 'Self'); ?></td>
                  <td><span class="badge <?php echo $m['status']=='taken' ? 'bg-success' : ($m['status']=='missed' ? 'bg-danger' : 'bg-secondary'); ?>"><?php echo $m['status']; ?></span></td>
                  <td>
                    <?php if($m['status'] === 'pending'): ?>
                      <a href="update_med.php?med_id=<?php echo $m['med_id']; ?>&action=taken" class="btn btn-sm btn-success">Mark Taken</a>
                      <a href="update_med.php?med_id=<?php echo $m['med_id']; ?>&action=missed" class="btn btn-sm btn-warning">Mark Missed</a>
                    <?php endif; ?>
                    <a href="delete_med.php?med_id=<?php echo $m['med_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this medicine?')">Delete</a>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header"><strong>Doctor Contacts</strong></div>
      <div class="card-body">
        <form method="post" action="add_doctor.php">
          <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
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
                <div>
                  <a href="tel:<?php echo htmlspecialchars($d['contact']); ?>" class="btn btn-sm btn-outline-primary">Call</a>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><strong>Notifications</strong></div>
      <div class="card-body p-0" id="notificationsArea">
  <ul class="list-group list-group-flush">
    <?php
    $stmt = $pdo->prepare("SELECT * FROM alerts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$uid]);
    $alerts = $stmt->fetchAll();
    if ($alerts) {
      foreach ($alerts as $alert) {
        $bg = ($alert['type']=='missed') ? 'bg-danger text-white'
            : (($alert['type']=='custom') ? 'bg-warning'
            : 'bg-info');
        echo '<li class="list-group-item d-flex justify-content-between align-items-center '.$bg.'" style="border-bottom:1px solid #eee;">';
          echo '<div>';
            if ($alert['type'] == 'missed') echo '<span class="me-2">&#9888;</span>';
            elseif ($alert['type'] == 'custom') echo '<span class="me-2">&#128276;</span>';
            else echo '<span class="me-2">&#9432;</span>';
            echo '<strong>' . htmlspecialchars($alert['message']) . '</strong>';
            echo '<br><small class="text-light">' . htmlspecialchars($alert['type']) . ' | ' . htmlspecialchars($alert['created_at']) . '</small>';
          echo '</div>';
          echo '<form method="post" action="delete_alert.php" style="margin:0">';
            echo '<input type="hidden" name="alert_id" value="' . (int)$alert['alert_id'] . '">';
            echo '<button type="submit" class="btn btn-sm btn-outline-light ms-2">Delete</button>';
          echo '</form>';
        echo '</li>';
      }
    } else {
      echo '<li class="list-group-item text-muted">No alerts.</li>';
    }
    ?>
  </ul>
</div>
    </div>
  </div>
</div>
</div>
</div>
<!-- Add Medicine Modal -->
<div class="modal fade" id="addMedModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="add_med.php">
      <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
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


<!-- ... (modals, scripts) as previously in your code ... (no changes needed) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // List of today's medication times and names
  var meds = [
    <?php foreach($medications as $med): ?>
      {
        name: "<?php echo addslashes($med['name']); ?>",
        time: "<?php echo date('Y-m-d').' '.substr($med['time'],0,5); ?>"
      },
    <?php endforeach; ?>
  ];


  function checkMedTime() {
    var now = new Date();
    meds.forEach(function(med) {
      // Construct medication datetime object for today
      var medTime = new Date(med.time);
      // If current time matches medication time to the minute
      if (now.getFullYear() === medTime.getFullYear() &&
          now.getMonth() === medTime.getMonth() &&
          now.getDate() === medTime.getDate() &&
          now.getHours() === medTime.getHours() &&
          now.getMinutes() === medTime.getMinutes()) {
        // Show popup
        alert("Medication Reminder: " + med.name + " (It's time!)");
      }
    });
  }


  // Check every 30 seconds for new reminders
  setInterval(checkMedTime, 30000);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var meds = [
    <?php foreach($medications as $med): ?>
      {
        med_id: <?php echo (int)$med['med_id']; ?>,
        name: "<?php echo addslashes($med['name']); ?>",
        time: "<?php echo date('Y-m-d').' '.substr($med['time'],0,5); ?>"
      },
    <?php endforeach; ?>
  ];
  var notifiedMeds = {};

  function showPopup(med) {
  notifiedMeds[med.med_id] = false;
  if (confirm("Medication Reminder: " + med.name + "\n(Click OK after you have taken it)")) {
    notifiedMeds[med.med_id] = true;
  }
  setTimeout(function() {
    if (!notifiedMeds[med.med_id]) {
      console.log('Sending missed notification for med:', med.med_id);
      fetch('notify_caretaker.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'med_id=' + encodeURIComponent(med.med_id)
      })
      .then(response => response.text())
      .then(data => console.log('Caretaker notify response:', data))
      .catch(err => console.error("Notify caretaker error:", err));
    }
  }, 10 * 60 * 1000); // 10 minutes
}


  function checkMedTime() {
    var now = new Date();
    meds.forEach(function(med) {
      var medTime = new Date(med.time);
      if (
        now.getFullYear() === medTime.getFullYear() &&
        now.getMonth() === medTime.getMonth() &&
        now.getDate() === medTime.getDate() &&
        now.getHours() === medTime.getHours() &&
        now.getMinutes() === medTime.getMinutes() &&
        !notifiedMeds[med.med_id]
      ) {
        showPopup(med);
      }
    });
  }

  setInterval(checkMedTime, 30000);
});
</script>

<?php include 'includes/footer.php'; ?>