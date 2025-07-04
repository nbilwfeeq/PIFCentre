<?php
include '../database/config.php';

if (isset($_POST['nokp'])) {
    $nokp = $_POST['nokp'];

    // Delete all notifications for the given user
    $deleteStmt = $connect->prepare("DELETE FROM notifications WHERE nokp = ?");
    if (!$deleteStmt) {
        die("Error preparing statement: " . $connect->error);
    }

    $deleteStmt->bind_param("s", $nokp);
    if ($deleteStmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
