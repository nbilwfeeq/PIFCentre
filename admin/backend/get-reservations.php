<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

// Get filter and pagination values from GET parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Prepare the SQL query with filters and pagination
$query = "SELECT r.*, b.judul_buku, b.isbn
          FROM reservations r
          JOIN books b ON r.id_buku = b.id_buku
          WHERE r.status_tempahan = 1"; // Only include rows with status_tempahan = 1

if (!empty($search)) {
    $query .= " AND (b.judul_buku LIKE ? OR r.nama_penuh LIKE ? OR r.reserve_date LIKE ?)";
}

$stmt = $connect->prepare($query);

$params = [];
$paramTypes = '';

if (!empty($search)) {
    $searchWildcard = "%$search%";
    $params = array_merge($params, [$searchWildcard, $searchWildcard, $searchWildcard]);
    $paramTypes .= 'sss';
}

if ($paramTypes) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch and format data
$rows = [];
while ($row = $result->fetch_assoc()) {
    $statusText = 'Menunggu Pengesahan';
    $statusColor = '#e2e72c';

    $rows[] = [
        'Bil' => $row['id_reservation'],
        'Nama Pengguna' => htmlspecialchars($row['nama_penuh']),
        'Judul Buku' => htmlspecialchars($row['judul_buku']),
        'Tarikh Tempahan' => htmlspecialchars($row['reserve_date']),
        'Tarikh Pemulangan' => htmlspecialchars($row['return_date']),
        'Status Tempahan' => "<span style='background-color: $statusColor; font-size: 15px;' class='badge rounded-pill'>$statusText</span>",
        'Tindakan' => "<button class='btn btn-success btn-sm' onclick='updateStatus({$row['id_reservation']}, 2)'>Sahkan</button>"
    ];
}

// Apply pagination
$rows = array_slice($rows, 0, $entries);

// Generate table rows
foreach ($rows as $row) {
    echo "<tr>";
    foreach ($row as $key => $value) {
        echo "<td>$value</td>";
    }
    echo "</tr>";
}

$stmt->close();
$connect->close();
?>
