<?php
include 'includes/db.php';
if(!isset($_GET['med_id']) || !isset($_GET['action'])) { header("Location: index.php"); exit;}
$med_id = intval($_GET['med_id']);
$action = $_GET['action'];
$status = ($action === 'taken') ? 'taken' : (($action==='missed') ? 'missed' : 'pending');

$stmt = $pdo->prepare("UPDATE medications SET status = ? WHERE med_id = ?");
$stmt->execute([$status,$med_id]);

// if missed -> create alert for caretaker
if($status === 'missed'){
    // find med, patient, caretaker
    $stmt = $pdo->prepare("SELECT user_id FROM medications WHERE med_id = ?");
    $stmt->execute([$med_id]);
    $m = $stmt->fetch();
    if($m){
      $patientId = $m['user_id'];
      // find caretaker
      $s = $pdo->prepare("SELECT linked_user FROM users WHERE user_id = ?");
      $s->execute([$patientId]);
      $r = $s->fetch();
      $caretakerId = $r ? $r['linked_user'] : null;
      if($caretakerId){
        $message = "Missed dose detected (med id: $med_id). Please check in with the patient.";
        $ins = $pdo->prepare("INSERT INTO alerts (user_id,caretaker_id,type,message) VALUES (?,?, 'missed_dose', ?)");
        $ins->execute([$patientId,$caretakerId,$message]);
      }
    }
}

header("Location: " . ($_SESSION['user']['role']=='caretaker' ? 'caretaker_dashboard.php' : 'patient_dashboard.php'));
exit;
