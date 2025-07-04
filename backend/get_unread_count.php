<?php
include '../database/config.php';

$nokp = $_POST['nokp'] ?? '';

if (!$nokp) {
    echo json_encode(['unreadCount' => 0]);
    exit;
}

$stmt = $connect->prepare("SELECT COUNT(*) AS total FROM notifications WHERE nokp = ? AND is_read = 0");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$result = $stmt->get_result();
$unreadCount = $result->fetch_assoc()['total'];

echo json_encode(['unreadCount' => $unreadCount]);
?>