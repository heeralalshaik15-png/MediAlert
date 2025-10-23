<?php
include 'includes/db.php';
if(!isset($_GET['med_id'])){ header("Location: index.php"); exit;}
$med_id = intval($_GET['med_id']);
// optional: check permissions
$stmt = $pdo->prepare("DELETE FROM medications WHERE med_id = ?");
$stmt->execute([$med_id]);
header("Location: " . ($_SESSION['user']['role']=='caretaker' ? 'caretaker_dashboard.php' : 'patient_dashboard.php'));
exit;
