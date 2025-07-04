<?php
include '../database/config.php';

if (isset($_POST['nokp'])) {
    $nokp = $_POST['nokp'];

    // Update notifications to mark them as read
    $stmt = $connect->prepare("UPDATE notifications SET is_read = 1 WHERE nokp = ? AND is_read = 0");
    if (!$stmt) {
        die("Error preparing statement: " . $connect->error);
    }
    $stmt->bind_param("s", $nokp);
    $stmt->execute();

    // Return success
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No user ID provided']);
}
?>
