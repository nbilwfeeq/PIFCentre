<?php
include '../database/config.php';

// Update all notifications as read
$updateQuery = "UPDATE notifications_admin SET is_read = 1 WHERE is_read = 0";
if ($connect->query($updateQuery)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $connect->error]);
}

?>