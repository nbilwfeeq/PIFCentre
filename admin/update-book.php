<?php 
include 'database/config.php';
include 'session/security.php';
$page = 'Kemaskini Data Buku | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';

// Get book data based on the ID
$isbn = isset($_GET['id']) ? $_GET['id'] : null;
if (!$isbn) {
    header("Location: books.php");
    exit;
}

// Fetch book details
$bookStmt = $connect->prepare("SELECT * FROM books WHERE isbn = ?");
$bookStmt->bind_param("i", $isbn);
$bookStmt->execute();
$bookResult = $bookStmt->get_result();
$book = $bookResult->fetch_assoc();

// Get jenis buku info
$jenisStmt = $connect->prepare("SELECT id_jenisBuku, jenisBuku FROM jenis");
$jenisStmt->execute();
$jenisResult = $jenisStmt->get_result();
$jenisBuku = $jenisResult->fetch_all(MYSQLI_ASSOC);

// Get kategori buku info
$kategoriStmt = $connect->prepare("SELECT id_kategoriBuku, kategoriBuku FROM kategori");
$kategoriStmt->execute();
$kategoriResult = $kategoriStmt->get_result();
$kategoriBuku = $kategoriResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">
    
    <!-- ==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <title><?php echo $page; ?></title>

    <style>
        .form-control-file {
            display: block;
            width: 50%;
        }
        .btn-custom {
            background-color: transparent;
            border: 2px solid black; 
            color: black;
            transition: all 0.1s ease; 
        }
        .btn-custom:hover {
            color: #fff;
            background-color: var(--orange); 
            border: none;
        }
    </style>
</head>

<body>

    <?php include('includes/topbar.php'); ?>

    <div class="content container p-3">
        <h1>Kemaskini Data Buku</h1>
        <div class="hr">
            <hr>
        </div>
        <form id="editForm" action="backend/edit-book.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_buku" value="<?php echo $book['id_buku']; ?>">
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengarang Buku</label>
                        <input type="text" name="pengarang_buku" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['pengarang_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penerbit Buku</label>
                        <input type="text" name="penerbit_buku" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['penerbit_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tempat Terbit</label>
                        <input type="text" name="tempat_terbit" class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['tempat_terbit']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" value="<?php echo htmlspecialchars($book['tahun_terbit']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Muka Surat</label>
                        <input type="number" name="mukasurat_buku" class="form-control" value="<?php echo htmlspecialchars($book['mukasurat_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>">
                    </div>
                </div>
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Jenis Buku</label>
                        <select name="id_jenisBuku" class="form-control" required>
                            <?php foreach ($jenisBuku as $jenis): ?>
                                <option value="<?php echo $jenis['id_jenisBuku']; ?>" <?php if ($book['id_jenisBuku'] == $jenis['id_jenisBuku']) echo 'selected'; ?>>
                                    <?php echo $jenis['jenisBuku']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Buku</label>
                        <select name="id_kategoriBuku" class="form-control" required>
                            <?php foreach ($kategoriBuku as $kategori): ?>
                                <option value="<?php echo $kategori['id_kategoriBuku']; ?>" <?php if ($book['id_kategoriBuku'] == $kategori['id_kategoriBuku']) echo 'selected'; ?>>
                                    <?php echo $kategori['kategoriBuku']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Perolehan Buku</label>
                        <input type="text" name="noPerolehan_buku" class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['noPerolehan_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Panggil Buku</label>
                        <input type="text" name="noPanggil_buku" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['noPanggil_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Buku</label>
                        <input type="text" name="lokasi_buku" class="form-control uppercase-text" value="<?php echo htmlspecialchars($book['lokasi_buku']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bahasa Buku</label>
                        <select name="bahasa_buku" class="form-control">
                            <option value="-" <?php if ($book['bahasa_buku'] == '-') echo 'selected'; ?>>-</option>
                            <option value="BM" <?php if ($book['bahasa_buku'] == 'BM') echo 'selected'; ?>>BAHASA MELAYU</option>
                            <option value="BI" <?php if ($book['bahasa_buku'] == 'BI') echo 'selected'; ?>>BAHASA INGGERIS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Buku</label>
                        <input type="file" name="gambar_buku" class="form-control-file">
                        <small class="text-muted">Biarkan kosong jika tidak mahu mengubah gambar.</small>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="books.php"><i class='bx bx-arrow-back'></i>&nbspKembali</a>
                        <button type="submit" name="update" class="btn btn-custom"><i class='bx bxs-edit-alt'></i>&nbspKemaskini Buku</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>

    <!--=============== Bootstrap 5.2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!--=============== jQuery ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!--=============== Datatables ===============-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Convert text inputs to uppercase on form submission
            $('#editForm').submit(function() {
                $('input.uppercase-text').each(function() {
                    $(this).val($(this).val().toUpperCase());
                });
            });
        });

        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

        if (getURL == 'updateError') {
            Swal.fire({
                icon: 'error',
                title: 'Kemaskini Buku Gagal!',
                text: 'Data buku tidak berjaya dikemaskini.'
            }).then(() => {
                // Remove the status=error parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }

    </script>

</body>
</html>
