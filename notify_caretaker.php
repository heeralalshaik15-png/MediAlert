<?php
include 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['med_id'])) {
    $med_id = intval($_POST['med_id']);
    // Lookup medication and caretaker
    $stmt = $pdo->prepare("SELECT m.name, m.user_id, u.linked_user AS caretaker_id FROM medications m JOIN users u ON m.user_id = u.user_id WHERE m.med_id = ?");
    $stmt->execute([$med_id]);
    $med = $stmt->fetch();
    if ($med && $med['caretaker_id']) {
        $msg = "Patient missed medication: " . $med['name'] . " (not acknowledged in 10 minutes)";
        $pdo->prepare("INSERT INTO alerts (user_id, caretaker_id, `type`, message) VALUES (?, ?, 'missed', ?)")
            ->execute([$med['user_id'], $med['caretaker_id'], $msg]);
        $pdo->prepare("UPDATE medications SET status='missed' WHERE med_id=?")->execute([$med_id]);

    }
    // You can update medication status here too if needed:
    // $pdo->prepare("UPDATE medications SET status='missed' WHERE med_id=?")->execute([$med_id]);
}
?>
