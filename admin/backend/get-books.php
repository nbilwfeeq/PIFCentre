<?php
include '../database/config.php';

// Initialize variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$id_jenisBuku = isset($_GET['id_jenisBuku']) ? $_GET['id_jenisBuku'] : '';
$id_kategoriBuku = isset($_GET['id_kategoriBuku']) ? $_GET['id_kategoriBuku'] : '';
$bahasa = isset($_GET['bahasa']) ? $_GET['bahasa'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate offset for pagination
$offset = ($page - 1) * $entries;

// Create SQL query with combined total count and results
$query = "
    WITH FilteredBooks AS (
        SELECT 
            b.id_buku, b.judul_buku, b.pengarang_buku, b.noPerolehan_buku, 
            b.noPanggil_buku, b.isbn, b.lokasi_buku, b.bahasa_buku, b.gambar_buku,
            b.tahun_terbit, b.mukaSurat_buku, b.penerbit_buku, 
            b.tempat_terbit, j.jenisBuku, k.kategoriBuku
        FROM books b
        LEFT JOIN jenis j ON b.id_jenisBuku = j.id_jenisBuku
        LEFT JOIN kategori k ON b.id_kategoriBuku = k.id_kategoriBuku
        WHERE 1=1
";

// Append filters
if ($search != '') {
    $query .= " AND (b.judul_buku LIKE ? OR b.pengarang_buku LIKE ? OR b.noPerolehan_buku LIKE ? OR b.noPanggil_buku LIKE ?)";
}
if ($id_jenisBuku != '') {
    $query .= " AND b.id_jenisBuku = ?";
}
if ($id_kategoriBuku != '') {
    $query .= " AND b.id_kategoriBuku = ?";
}
if ($bahasa != '') {
    $query .= " AND b.bahasa_buku = ?";
}

$query .= "
    )
    SELECT *, (SELECT COUNT(DISTINCT isbn) FROM FilteredBooks) AS total_records
    FROM FilteredBooks
    GROUP BY isbn
    LIMIT ? OFFSET ?
";

// Prepare and execute the statement
$stmt = $connect->prepare($query);

if (!$stmt) {
    die("Failed to prepare statement: " . $connect->error);
}

// Bind parameters dynamically
$paramTypes = '';
$params = [];

if ($search != '') {
    $searchParam = "%$search%";
    $paramTypes .= 'ssss';
    $params[] = &$searchParam;
    $params[] = &$searchParam;
    $params[] = &$searchParam;
    $params[] = &$searchParam;
}
if ($id_jenisBuku != '') {
    $paramTypes .= 'i';
    $params[] = &$id_jenisBuku;
}
if ($id_kategoriBuku != '') {
    $paramTypes .= 'i';
    $params[] = &$id_kategoriBuku;
}
if ($bahasa != '') {
    $paramTypes .= 's';
    $params[] = &$bahasa;
}

// Bind limit and offset parameters
$paramTypes .= 'ii';
$params[] = &$entries;
$params[] = &$offset;

if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Initialize index
$index = $offset + 1;

// Fetch data and construct table rows
while ($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $index++ . '</td>
            <td>' . htmlspecialchars($row['judul_buku']) . '</td>
            <td>' . htmlspecialchars($row['jenisBuku']) . '</td>
            <td>' . htmlspecialchars($row['kategoriBuku']) . '</td>
            <td>' . htmlspecialchars($row['noPanggil_buku']) . '</td>
            <td>' . htmlspecialchars($row['isbn']) . '</td>
            <td>' . htmlspecialchars($row['lokasi_buku']) . '</td>
            <td>' . htmlspecialchars($row['bahasa_buku']) . '</td>
            <td>
                <form method="POST" action="backend/delete-book-quantity.php" style="display:inline;">
                    <input type="hidden" name="isbn" value="' . htmlspecialchars($row['isbn'], ENT_QUOTES) . '">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bx bx-minus" ></i></button>
                </form>
                ' . htmlspecialchars($row['total_records']) . '
                <form method="POST" action="backend/add-book-quantity.php" style="display:inline;">
                    <input type="hidden" name="isbn" value="' . htmlspecialchars($row['isbn'], ENT_QUOTES) . '">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bx bx-plus"></i></button>
                </form>
            </td>
            <td><img src="../images/books/' . htmlspecialchars($row['gambar_buku']) . '" alt="' . htmlspecialchars($row['judul_buku']) . '" width="50"></td>
            <td>
                <a href="javascript:void(0);" onclick="editBook(\'' . htmlspecialchars($row['id_buku']) . '\');" class="btn btn-warning btn-sm">
                    <i class="bx bx-edit"></i>
                </a>
                <a href="javascript:void(0);" 
                    onclick="deleteBook(\'' . htmlspecialchars($row['isbn'], ENT_QUOTES) . '\');" 
                    class="btn btn-danger btn-sm">
                    <i class="bx bx-trash"></i>
                </a>
          </tr>';
}

// Fetch the total record count from the last row (total_records)
$totalRecords = $result->fetch_assoc()['total_records'];

// Output total records as a hidden field
echo '<input type="hidden" id="totalRecords" value="' . $totalRecords . '">';

$stmt->close();
$connect->close();
?>
