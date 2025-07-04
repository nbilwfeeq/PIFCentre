<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blockedDates = json_decode($_POST['blocked_dates'], true);

    // Clear existing blocked dates
    $connect->query("DELETE FROM blocked_dates");

    // Insert the new blocked dates
    $stmt = $connect->prepare("INSERT INTO blocked_dates (blocked_date) VALUES (?)");
    foreach ($blockedDates as $date) {
        $stmt->bind_param("s", $date);
        $stmt->execute();
    }
    echo json_encode(['status' => 'success']);
}
?>