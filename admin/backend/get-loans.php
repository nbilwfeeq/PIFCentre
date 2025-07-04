<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

// Get filter and pagination values from GET parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$stat = isset($_GET['stat']) ? $_GET['stat'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset based on the current page and entries per page
$offset = ($page - 1) * $entries;

// Update fines for overdue loans
$today = date('Y-m-d');
$fineRate = 0.20;

// SQL to update fines for overdue books
$updateFineQuery = "
    UPDATE loans
    SET fine = fine + ? * DATEDIFF(?, return_date)
    WHERE return_date < ? 
    AND status_pinjaman != 2
    AND DATEDIFF(?, return_date) > 0";
$updateStmt = $connect->prepare($updateFineQuery);
$updateStmt->bind_param('dsss', $fineRate, $today, $today, $today);
$updateStmt->execute();
$updateStmt->close();

// Prepare the SQL query with filters and pagination
$query = "SELECT r.*, b.judul_buku, b.isbn 
          FROM loans r
          JOIN books b ON r.id_buku = b.id_buku
          WHERE 1=1";

// Apply search filter
if (!empty($search)) {
    $query .= " AND (b.judul_buku LIKE ? OR r.nama_penuh LIKE ? OR r.reserve_date LIKE ?)";
}

// Apply status filter
if (!empty($stat)) {
    $query .= " AND r.status_pinjaman = ?";
}

// Add LIMIT and OFFSET for pagination
$query .= " LIMIT ? OFFSET ?";

// Prepare the statement
$stmt = $connect->prepare($query);

$params = [];
$paramTypes = '';

// Add search parameters if search filter is applied
if (!empty($search)) {
    $searchWildcard = "%$search%";
    $params = array_merge($params, [$searchWildcard, $searchWildcard, $searchWildcard]);
    $paramTypes .= 'sss';
}

// Add status filter if status is set
if (!empty($stat)) {
    $params[] = $stat;
    $paramTypes .= 's';
}

// Add entries per page and offset for pagination
$params[] = $entries;
$params[] = $offset;
$paramTypes .= 'ii'; // 'ii' for two integers: entries per page and offset

// Bind parameters and execute
$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and format data
$rows = [];
while ($row = $result->fetch_assoc()) {
    $statusText = ($row['status_pinjaman'] == 0) ? 'Menunggu Tuntutan' : (($row['status_pinjaman'] == 1) ? 'Buku Dipinjam' : 'Buku Dipulangkan');
    $statusColor = ($row['status_pinjaman'] == 0) ? '#e2e72c' : (($row['status_pinjaman'] == 1) ? '#71df87' : '#17a2b8');

    // Action buttons logic
    $actionButton = '';
    if ($row['status_pinjaman'] == 1) {
        // If the status is 1 (Buku Dituntut), show both the "Batal" and "Pulang Buku" buttons
        $actionButton = "
            <button class='btn btn-danger btn-sm' onclick='cancelLoan({$row['id_loan']})'>Batal</button>
            <button class='btn btn-success btn-sm' onclick='markAsReturned({$row['id_loan']})'>Dipulangkan</button>
        ";
    } elseif ($row['status_pinjaman'] == 0) {
        // If the status is 0 (Menunggu Tuntutan), show the "Selesai" button that sets status to 1
        $actionButton = "<button class='btn btn-primary btn-sm' onclick='markAsCompleted({$row['id_loan']})'>Dituntut</button>";
    } elseif ($row['status_pinjaman'] == 2) {
        // If the status is 2 (Buku Dipulangkan), show the "Selesai" button to delete both the loan and reservation
        $actionButton = "<button class='btn btn-success btn-sm' onclick='deleteLoanAndReservation({$row['id_loan']}, {$row['id_buku']}, {$row['id_reservation']})'>Simpan Rekod</button>";
    }

    $rows[] = [
        'Bil' => $row['id_loan'],
        'Nama Pengguna' => htmlspecialchars($row['nama_penuh']),
        'No. Kad Pengenalan' => htmlspecialchars($row['nokp']),
        'Judul Buku' => htmlspecialchars($row['judul_buku']),
        'Tarikh Pemulangan' => htmlspecialchars($row['return_date']),
        'Denda (RM)' => number_format($row['fine'] ?? 0, 2), // Use 0 if fine is NULL
        'Status Tempahan' => "<span style='background-color: $statusColor; font-size: 15px;' class='badge rounded-pill'>$statusText</span>",
        'Tindakan' => $actionButton
    ];
}

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

<script>
    // JavaScript function to mark a loan as completed (from Menunggu Tuntutan to Buku Dituntut)
    function markAsCompleted(id_loan) {
        if (confirm('Adakah buku ini telah dituntut?')) {
            // Redirect to the update-loan-status.php page with the id_loan and status=1 (Buku Dituntut)
            window.location.href = 'update-loan-status.php?id_loan=' + id_loan + '&status=1';
        }
    }

    // JavaScript function to mark a loan as returned (from Buku Dituntut to Buku Dipulangkan)
    function markAsReturned(id_loan) {
        if (confirm('Adakah buku ini telah dipulangkan?')) {
            // Redirect to the update-loan-status.php page with the id_loan and status=2 (Buku Dipulangkan)
            window.location.href = 'update-loan-status.php?id_loan=' + id_loan + '&status=2';
        }
    }

    // JavaScript function to cancel a loan (from Menunggu Tuntutan or Buku Dituntut to Canceled)
    function cancelLoan(id_loan) {
        if (confirm('Adakah anda yakin untuk membatalkan pinjaman ini?')) {
            // Redirect to the update-loan-status.php page with the id_loan and status=0 (Cancelled)
            window.location.href = 'update-loan-status.php?id_loan=' + id_loan + '&status=0';
        }
    }

    // JavaScript function to delete both loan and reservation (when status is 2)
    function deleteLoanAndReservation(id_loan, id_buku, id_reservation) {
        if (confirm('Simpan dalam rekod pinjaman?')) {
            // Correctly construct the URL with query parameters
            window.location.href = 'backend/delete-loan-and-reservation.php?id_loan=' + id_loan + '&id_buku=' + id_buku + '&id_reservation=' + id_reservation;
        }
    }

</script>
