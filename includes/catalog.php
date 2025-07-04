<?php

// Get jenis buku info
$jenisStmt = $connect->prepare("SELECT * FROM jenis");
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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$selectedJenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
$selectedKategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$query = "SELECT b.id_buku, b.judul_buku, j.jenisBuku, k.kategoriBuku, b.noPerolehan_buku, b.noPanggil_buku, b.lokasi_buku, b.bahasa_buku, b.gambar_buku,
                 (SELECT SUM(b2.kuantiti_buku)
                  FROM books b2
                  WHERE b2.noPanggil_buku = b.noPanggil_buku) AS kuantiti_buku
          FROM books b
          LEFT JOIN jenis j ON b.id_jenisBuku = j.id_jenisBuku
          LEFT JOIN kategori k ON b.id_kategoriBuku = k.id_kategoriBuku
          WHERE b.id_buku IN (
              SELECT MIN(id_buku)
              FROM books
              GROUP BY noPanggil_buku
          )";

$params = [];
$types = '';

if ($search) {
    $query .= " AND b.judul_buku LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= 's';
}

if ($selectedJenis) {
    $query .= " AND b.id_jenisBuku = ?";
    $params[] = $selectedJenis;
    $types .= 's';
}

if ($selectedKategori) {
    $query .= " AND b.id_kategoriBuku = ?";
    $params[] = $selectedKategori;
    $types .= 's';
}

// Prepare and execute the statement
$booksStmt = $connect->prepare($query);
if (!$booksStmt) {
    die("Error preparing statement: " . $connect->error);
}

if (!empty($types)) {
    $booksStmt->bind_param($types, ...$params);
}
$booksStmt->execute();
$booksResult = $booksStmt->get_result();
$books = $booksResult->fetch_all(MYSQLI_ASSOC);

?>

<style>
    .page-link {
        color: var(--cedar); 
        transition: all 0.3s ease; 
    }
    .page-item.active .page-link {
        background-color: var(--cedar); 
        color: #fff; 
        border-color: var(--cedar); 
        box-shadow: 0 0 10px var(--cedar); 
    }
    .page-link:hover {
        background-color: rgba(0, 0, 0, 0.1);
        color: var(--cedar);
    }
    
    /* Custom CSS for Book Cards */
    .card {
        margin-bottom: 0px;
    }
    .card img {
        max-height: 100%; /* Adjust image height */
        object-fit: cover;
        border-radius: 5px;
    }
    .card {
        background-color: transparent;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all ease 0.5s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Default box-shadow */
    }
    .card:hover {
        box-shadow: 0 30px 40px var(--light-cream);
        border: 1px solid var(--light-cream);
        transform: scale(1.1);
        }
    .card-body {
        flex: 1; /* Ensures the card body takes up available space */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-body .btn-custom {
        align-self: flex-end; /* Align the button to the bottom */
        margin-top: auto; /* Pushes the button to the bottom of the card body */
    }
    .card-body h5 {
        transition: color 0.5s;
        color: #000; /* Default text color */
    }
    .card:hover .card-body i {
        color: var(--light-cream);
    }
    .card:hover .card-body h5 {
        color: var(--light-cream);
    }
    .catalog-title {
        font-family: "Quicksand";
        font-size: 3.0em;
        font-weight: none;
        color: var(--cedar);
    }

</style>

    <div class="container mt-5 height-100">
        <br>
        <h1 class="catalog-title" data-aos="fade-right">Katalog Buku</h1>
        <br>
        <!-- <form method="get" action="catalog.php" class="mb-4 d-flex justify-content-end">
            <div class="input-group" style="max-width: 800px;">
                <input type="text" class="form-control" name="search" placeholder="Cari Judul Buku..." value="<?php echo htmlspecialchars($search); ?>" style="max-width: 300px;">
                <select class="form-select" name="jenis" style="max-width: 230px;">
                    <option value="">--Semua Jenis Buku--</option>
                    <?php foreach ($jenisBuku as $jenis): ?>
                        <option value="<?php echo htmlspecialchars($jenis['id_jenisBuku']); ?>" <?php echo ($selectedJenis == $jenis['id_jenisBuku']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($jenis['jenisBuku']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select class="form-select" name="kategori" style="max-width: 230px;">
                    <option value="">--Semua Kategori Buku--</option>
                    <?php foreach ($kategoriBuku as $kategori): ?>
                        <option value="<?php echo htmlspecialchars($kategori['id_kategoriBuku']); ?>" <?php echo ($selectedKategori == $kategori['id_kategoriBuku']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kategori['kategoriBuku']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form> -->

        <div class="row" id="bookList">
            <?php foreach ($books as $index => $book): ?>
                <div class="col-lg-3 col-md-6 mb-4 book-card" data-page="<?php echo floor($index / 4); ?>">
                    <div class="card">
                        <a href="login-page.php?id=<?php echo htmlspecialchars($book['id_buku']); ?>" >
                            <img src="images/books/<?php echo htmlspecialchars($book['gambar_buku']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center mt-4" id="paginationControls">
                <!-- Pagination buttons will be generated here dynamically -->
            </ul>
        </nav>
    </div>

    <!--===========Bootstrap===========-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--=========== jQuery ===========-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

   <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const booksPerPage = 4; // Number of books per page
            const bookCards = document.querySelectorAll('.book-card');
            const totalPages = Math.ceil(bookCards.length / booksPerPage);
            const paginationControls = document.getElementById('paginationControls');
            const maxVisiblePages = 3; // Max pagination buttons visible

            function showPage(page) {
                // Hide all book cards
                bookCards.forEach(card => card.style.display = 'none');

                // Show only the cards for the current page
                bookCards.forEach(card => {
                    if (parseInt(card.dataset.page) === page) {
                        card.style.display = 'block';
                    }
                });
            }

            function createPaginationButtons(currentPage = 0) {
                paginationControls.innerHTML = '';

                const startPage = Math.max(0, currentPage - Math.floor(maxVisiblePages / 2));
                const endPage = Math.min(totalPages - 1, startPage + maxVisiblePages - 1);

                // Add "Previous" button
                if (currentPage > 0) {
                    const prevLi = document.createElement('li');
                    prevLi.className = 'page-item';
                    prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
                    prevLi.addEventListener('click', function (e) {
                        e.preventDefault();
                        createPaginationButtons(currentPage - 1);
                        showPage(currentPage - 1);
                    });
                    paginationControls.appendChild(prevLi);
                }

                // Add numbered page buttons
                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i + 1}</a>`;
                    li.addEventListener('click', function (e) {
                        e.preventDefault();
                        createPaginationButtons(i);
                        showPage(i);
                    });
                    paginationControls.appendChild(li);
                }

                // Add "Next" button
                if (currentPage < totalPages - 1) {
                    const nextLi = document.createElement('li');
                    nextLi.className = 'page-item';
                    nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
                    nextLi.addEventListener('click', function (e) {
                        e.preventDefault();
                        createPaginationButtons(currentPage + 1);
                        showPage(currentPage + 1);
                    });
                    paginationControls.appendChild(nextLi);
                }
            }

            // Initialize the book list and pagination
            showPage(0); // Show the first page
            createPaginationButtons(0); // Create pagination starting from page 0
        });
    </script>