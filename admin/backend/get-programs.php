<?php
include '../database/config.php';

// Fetch distinct programs
$query = "SELECT DISTINCT program FROM user WHERE program IS NOT NULL";
$result = $connect->query($query);

$options = '<option value="">--Semua Program--</option>';
while ($row = $result->fetch_assoc()) {
    $options .= '<option value="' . htmlspecialchars($row['program']) . '">' . htmlspecialchars($row['program']) . '</option>';
}

echo $options;
?>