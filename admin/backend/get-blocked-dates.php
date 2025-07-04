<?php
include '../database/config.php';

$result = $connect->query("SELECT blocked_date FROM blocked_dates");
$blockedDates = [];
while ($row = $result->fetch_assoc()) {
    $blockedDates[] = $row['blocked_date'];
}
echo json_encode($blockedDates);
?>