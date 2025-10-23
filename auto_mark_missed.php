<?php
include 'includes/db.php';

// Get current time in "HH:MM" format (same as stored in your database)
// Use server time; adjust if needed to match user timezone
$now = date('H:i');

// Find all PENDING medications where the time is IN THE PAST (not yet taken/missed)
$stmt = $pdo->query("SELECT * FROM medications WHERE status='pending' AND time < '$now'");
foreach ($stmt as $m) {
    // Step 1: Mark as 'missed'
    $update = $pdo->prepare("UPDATE medications SET status='missed' WHERE med_id=?");
    $update->execute([$m['med_id']]);

    // Step 2: Notify caretaker (your update_med.php logic)
    $patientId = $m['user_id'];
    $s = $pdo->prepare("SELECT linked_user FROM users WHERE user_id = ?");
    $s->execute([$patientId]);
    $r = $s->fetch();
    $caretakerId = $r ? $r['linked_user'] : null;
    if($caretakerId){
        $message = "Missed dose detected (med id: " . $m['med_id'] . "). Please check in with the patient.";
        $ins = $pdo->prepare("INSERT INTO alerts (user_id,caretaker_id,type,message) VALUES (?,?, 'missed_dose', ?)");
        $ins->execute([$patientId,$caretakerId,$message]);
    }
}
?>
