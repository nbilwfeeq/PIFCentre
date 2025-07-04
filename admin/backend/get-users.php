<?php
session_start();
include '../database/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$jawatan = isset($_GET['jawatan']) ? $_GET['jawatan'] : '';
$program = isset($_GET['program']) ? $_GET['program'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;

// Build the query
$query = "SELECT * FROM user WHERE (nama_penuh LIKE ? OR email LIKE ? OR nokp LIKE ?)";
$params = ["%$search%", "%$search%", "%$search%"];

if ($jawatan) {
    $query .= " AND jawatan = ?";
    $params[] = $jawatan;
}

if ($program) {
    $query .= " AND program = ?";
    $params[] = $program;
}

if ($tahun) {
    $query .= " AND tahun = ?";
    $params[] = $tahun;
}

$stmt = $connect->prepare($query);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$index = 1;
while ($data = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$index}</td>
        <td>" . htmlspecialchars($data['email']) . "</td>
        <td>" . htmlspecialchars($data['nama_penuh']) . "</td>
        <td>" . htmlspecialchars($data['nokp']) . "</td>
        <td>" . htmlspecialchars($data['notel']) . "</td>
        <td>" . htmlspecialchars($data['matriks']) . "</td>
        <td>" . htmlspecialchars($data['jawatan']) . "</td>
        <td>" . htmlspecialchars($data['tahun']) . "</td>
        <td>" . htmlspecialchars($data['program']) . "</td>
        <td>
            <button class='btn btn-warning btn-sm' onclick=\"editUser('" . htmlspecialchars($data['nokp']) . "')\" title='Kemaskini'><i class='bx bx-edit-alt'></i></button>
            <button class='btn btn-danger btn-sm' onclick=\"deleteUser('" . htmlspecialchars($data['nokp']) . "')\" title='Padam'><i class='bx bx-trash'></i></button>
        </td>
    </tr>";
    $index++;
}
?>
