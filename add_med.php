<?php
include 'includes/db.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $dosage = trim($_POST['dosage']);
    $time = $_POST['time'];
    $added_by = $_SESSION['user']['user_id'];

    $stmt = $pdo->prepare("INSERT INTO medications (user_id,name,dosage,time,added_by,status,created_at) VALUES (?,?,?,?,?,'pending',NOW())");
    $stmt->execute([$user_id,$name,$dosage,$time,$added_by]);

    header("Location: " . ($_SESSION['user']['role']=='caretaker' ? 'caretaker_dashboard.php' : 'patient_dashboard.php'));
    exit;
}
error_log('ROLE IS: ' . $_SESSION['user']['role']);

header("Location: index.php");
