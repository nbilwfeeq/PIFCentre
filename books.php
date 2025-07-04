<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database/config.php'; // Ensure this file includes correct database connection
include 'session/security.php'; // Ensure this file includes user authentication checks

// check if user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php?status=unauthorized");
    exit();
}

// Set nokp to user_id
$nokp = $_SESSION['user_id'];

// Get user info
$userStmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
if (!$userStmt) {
    die("Error preparing statement: " . $connect->error);
}
$userStmt->bind_param("s", $nokp);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

// Get jenis buku info
$jenisStmt = $connect->prepare("SELECT * FROM jenis WHERE id_jenisBuku");
if (!$jenisStmt) {
    die("Error preparing statement: " . $connect->error);
}
$jenisStmt->execute();
$jenisResult = $jenisStmt->get_result();
$jenisBuku = $jenisResult->fetch_all(MYSQLI_ASSOC);

// Get kategori buku info
$kategoriStmt = $connect->prepare("SELECT * FROM kategori");
if (!$kategoriStmt) {
    die("Error preparing statement: " . $connect->error);
}
$kategoriStmt->execute();
$kategoriResult = $kategoriStmt->get_result();
$kategoriBuku = $kategoriResult->fetch_all(MYSQLI_ASSOC);

// Get books info with optional search, jenis, and kategori
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedJenis = isset($_GET['jenis']) && $_GET['jenis'] !== '' ? $_GET['jenis'] : null;
$selectedKategori = isset($_GET['kategori']) && $_GET['kategori'] !== '' ? $_GET['kategori'] : null;

// Pagination
$limit = 12; // Number of books per page (2 rows x 6 books)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Base query
$query = "SELECT 
              MIN(b.id_buku) AS id_buku, -- Use the first ID for the book
              b.judul_buku, 
              j.jenisBuku, 
              k.kategoriBuku, 
              b.noPerolehan_buku, 
              b.noPanggil_buku, 
              b.lokasi_buku, 
              b.bahasa_buku, 
              b.gambar_buku,
              b.pengarang_buku, 
              b.isbn,           
              b.penerbit_buku,   
              b.tempat_terbit,   
              b.tahun_terbit,   
              b.mukasurat_buku,  
              SUM(b.kuantiti_buku) AS kuantiti_buku
          FROM books b
          LEFT JOIN jenis j ON b.id_jenisBuku = j.id_jenisBuku
          LEFT JOIN kategori k ON b.id_kategoriBuku = k.id_kategoriBuku
          WHERE 1=1";

// Prepare WHERE conditions dynamically
$conditions = [];
$params = [];
$types = '';

// Add jenis filter if selected
if (!empty($selectedJenis) && $selectedJenis != '0') {
    $conditions[] = "b.id_jenisBuku = ?";
    $params[] = $selectedJenis;
    $types .= 'i'; // integer type
}

// Add kategori filter if selected
if (!empty($selectedKategori) && $selectedKategori != '0') {
    $conditions[] = "b.id_kategoriBuku = ?";
    $params[] = $selectedKategori;
    $types .= 'i'; // integer type
}

// Append conditions to the query
if (!empty($conditions)) {
    $query .= " AND " . implode(" AND ", $conditions);
}

// Add GROUP BY clause to ensure unique ISBNs
$query .= " GROUP BY b.isbn";

// Add pagination limit and offset
$query .= " LIMIT ? OFFSET ?";

// Prepare statement
$booksStmt = $connect->prepare($query);
if (!$booksStmt) {
    die("Error preparing statement: " . $connect->error);
}

// Bind parameters if any
if (!empty($params)) {
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii'; // Adding the types for limit and offset (integers)
    $booksStmt->bind_param($types, ...$params);
} else {
    $booksStmt->bind_param('ii', $limit, $offset);
}

// Execute query
$booksStmt->execute();
$booksResult = $booksStmt->get_result();
$books = $booksResult->fetch_all(MYSQLI_ASSOC);

// Get total number of books for pagination
$totalBooksStmt = $connect->prepare("SELECT COUNT(DISTINCT b.isbn) AS total FROM books b LEFT JOIN jenis j ON b.id_jenisBuku = j.id_jenisBuku LEFT JOIN kategori k ON b.id_kategoriBuku = k.id_kategoriBuku WHERE 1=1");
if (!empty($conditions)) {
    $totalBooksStmt->bind_param($types, ...$params);
}
$totalBooksStmt->execute();
$totalBooksResult = $totalBooksStmt->get_result();
$totalBooks = $totalBooksResult->fetch_assoc()['total'];

$totalPages = ceil($totalBooks / $limit); // Calculate total pages

$page = 'Katalog | Ibnu Firnas Knowledge Centre';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/books-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <!--=============== Font Awesome ===============-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Custom CSS for Book Cards */
        .card {
            margin-bottom: 0px;
        }
        .card img {
            max-height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
    
    <title><?php echo htmlspecialchars($page); ?></title>
</head>
<body> 

    <?php include 'includes/sidebar.php'; ?>

    <!--Container Main start-->
    <div class="container mt-5 height-100">
        <br>
        <h1>Katalog Buku</h1>
        <p><?php echo htmlspecialchars($user['nama_penuh']); ?> - <?php echo htmlspecialchars($user['program']); ?></p>
        <br>

        <div class="row" id="bookList">
            <?php foreach ($books as $book): ?>
                <a href="book-detail.php?id=<?php echo htmlspecialchars($book['id_buku']); ?>" class="col-md-2 mb-4 text-decoration-none" title="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                    <div class="card">
                        <img src="images/books/<?php echo htmlspecialchars($book['gambar_buku']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                    </div>
                    <!-- Card Title Underneath -->
                    <div class="card-title text-center mt-2">
                        <h6 class="text-dark"><?php echo htmlspecialchars($book['judul_buku']); ?></h6>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="books.php?page=<?php echo $page - 1; ?>" class="prev">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="books.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="books.php?page=<?php echo $page + 1; ?>" class="next">Next &raquo;</a>
            <?php endif; ?>
        </div>


    </div>
    <!--Container Main end-->

    <!--===========Bootstrap===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--=========== jQuery ===========-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

</body>
</html>
