<?php
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $patient_id = $_POST['patient_id'];
  $date = $_POST['date'];
  $time = $_POST['time'];
  $desc = $_POST['desc'];
  $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, date, time, description) VALUES (?, ?, ?, ?)");
  $stmt->execute([$patient_id, $date, $time, $desc]);
  // Insert alert (popup for patient dashboard)
  $caretaker_id = $_SESSION['user']['user_id'];
  $msg = "You have a new appointment scheduled on $date at $time: $desc";
  $ins = $pdo->prepare("INSERT INTO alerts (user_id, caretaker_id, type, message) VALUES (?, ?,'appointment', ?)");
  $ins->execute([$patient_id, $caretaker_id, $msg]);
  header('Location: caretaker_dashboard.php?appointment=success');
  exit;
}
