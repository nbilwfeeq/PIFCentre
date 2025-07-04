<?php
include '../database/config.php';

if (isset($_POST['id_notification']) && isset($_POST['nokp'])) {
    $id_notification = intval($_POST['id_notification']);
    $nokp = $_POST['nokp'];

    // Delete notification for the user
    $deleteStmt = $connect->prepare("DELETE FROM notifications WHERE id_notification = ? AND nokp = ?");
    if (!$deleteStmt) {
        die("Error preparing statement: " . $connect->error);
    }

    $deleteStmt->bind_param("is", $id_notification, $nokp);

    if ($deleteStmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
