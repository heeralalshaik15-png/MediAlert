<?php
include 'includes/db.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header("Location: index.php"); exit; }
$user_id = intval($_POST['user_id']);
// find caretaker
$stmt = $pdo->prepare("SELECT linked_user FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$r = $stmt->fetch();
$caretaker = $r ? $r['linked_user'] : null;
if($caretaker){
    $message = "SOS triggered by patient (ID: $user_id). Please check immediately.";
    $ins = $pdo->prepare("INSERT INTO alerts (user_id,caretaker_id,type,message) VALUES (?,?, 'sos', ?)");
    $ins->execute([$user_id,$caretaker,$message]);
}
header("Location: patient_dashboard.php");
exit;
