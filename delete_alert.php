<?php
include 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alert_id']) && isset($_SESSION['user'])) {
    $alert_id = intval($_POST['alert_id']);
    // Only let the alert owner delete their own alerts:
    $stmt = $pdo->prepare("DELETE FROM alerts WHERE alert_id = ? AND user_id = ?");
    $stmt->execute([$alert_id, $_SESSION['user']['user_id']]);
}
header("Location: patient_dashboard.php");
exit;
?>
