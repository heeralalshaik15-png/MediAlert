<?php
include 'includes/db.php';
session_start(); // Make sure session is started!

$for = $_GET['for'] ?? 'patient'; // 'patient' or 'caretaker'

if($for == 'caretaker'){
    // show alerts for this caretaker
    $caretakerId = $_SESSION['user']['user_id'];
    $stmt = $pdo->prepare("SELECT a.*, u.name as patient_name FROM alerts a JOIN users u ON a.user_id = u.user_id WHERE a.caretaker_id = ? ORDER BY a.created_at DESC");
    $stmt->execute([$caretakerId]);
    $alerts = $stmt->fetchAll();

    // --- JSON support for AJAX ---
    if(isset($_GET['json']) && $_GET['json']) {
        header('Content-Type: application/json');
        $result = [];
        foreach($alerts as $a){
            $result[] = [
                'type' => $a['type'],
                'message' => $a['message']
            ];
        }
        echo json_encode($result);
        exit;
    }

    if(!$alerts) { echo "<p class='text-muted'>No alerts.</p>"; exit; }
    echo "<ul class='list-group'>";
    foreach($alerts as $a){
        echo "<li class='list-group-item d-flex justify-content-between align-items-start'>";
        echo "<div><div class='fw-semibold'>".htmlspecialchars(ucfirst($a['type']))." — ".htmlspecialchars($a['patient_name'])."</div>";
        echo "<small class='text-muted'>".htmlspecialchars($a['message'])."</small></div>";
        echo "<div><small class='text-muted'>" . date('d M H:i', strtotime($a['created_at'])) . "</small></div></li>";
    }
    echo "</ul>";
    exit;
} else {
    // patient notifications show recent missed alerts related to this patient
    $patientId = $_SESSION['user']['user_id'];
    $stmt = $pdo->prepare("SELECT a.*, u.name as caretaker_name FROM alerts a JOIN users u ON a.caretaker_id = u.user_id WHERE a.user_id = ? ORDER BY a.created_at DESC");
    $stmt->execute([$patientId]);
    $alerts = $stmt->fetchAll();

    // --- JSON support for AJAX ---
    if(isset($_GET['json']) && $_GET['json']) {
        header('Content-Type: application/json');
        $result = [];
        foreach($alerts as $a){
            $result[] = [
                'type' => $a['type'],
                'message' => $a['message']
            ];
        }
        echo json_encode($result);
        exit;
    }

    if(!$alerts) { echo "<p class='text-muted'>No notifications.</p>"; exit; }
    echo "<ul class='list-group'>";
    foreach($alerts as $a){
        echo "<li class='list-group-item'><div class='fw-semibold'>".htmlspecialchars(ucfirst($a['type']))." — From ".htmlspecialchars($a['caretaker_name'])."</div>";
        echo "<small class='text-muted'>".htmlspecialchars($a['message'])." — ".date('d M H:i', strtotime($a['created_at']))."</small></li>";
    }
    echo "</ul>";
    exit;
}
?>
