<?php
include 'includes/db.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user_id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $spec = trim($_POST['specialization']);
    $contact = trim($_POST['contact']);

    $stmt = $pdo->prepare("INSERT INTO doctors (user_id,name,specialization,contact) VALUES (?,?,?,?)");
    $stmt->execute([$user_id,$name,$spec,$contact]);

    // redirect back to appropriate dashboard
    if($_SESSION['user']['role'] == 'caretaker') header("Location: caretaker_dashboard.php");
    else header("Location: patient_dashboard.php");
    exit;
}
header("Location: index.php");
