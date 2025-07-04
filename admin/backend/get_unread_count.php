<?php
include '../database/config.php';

$id_notification = $_POST['id_notification'] ?? '';

if (!$id_notification) {
    echo json_encode(['unreadCount' => 0]);
    exit;
}

$stmt = $connect->prepare("SELECT COUNT(*) AS total FROM notifications_admin WHERE id_notification = ? AND is_read = 0");
$stmt->bind_param("s", $id_notification);
$stmt->execute();
$result = $stmt->get_result();
$unreadCount = $result->fetch_assoc()['total'];

echo json_encode(['unreadCount' => $unreadCount]);
?>