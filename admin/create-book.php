<?php 
    include 'database/config.php';
    include 'session/security.php';
    $page = 'Tambah Data Buku | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';

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
    
    <link rel="shortcut icon" href="images/pif-icon-white.png.png" type="image/x-icon">
    
    <!-- ==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <h1>Tambah Maklumat Buku</h1>
        <div class="hr">
            <hr>
        </div>
        <form id="addForm" action="backend/add-book.php" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" required class="form-control uppercase-text" placeholder="Masukkan Judul Buku">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pengarang Buku</label>
                        <input type="text" name="pengarang_buku" required class="form-control uppercase-text" placeholder="Masukkan Pengarang Buku">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Penerbit Buku</label>
                        <input type="text" name="penerbit_buku" required class="form-control uppercase-text" placeholder="Masukkan No. Panggil Buku">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tempat Terbit</label>
                        <input type="text" name="tempat_terbit" class="form-control uppercase-text" placeholder="Masukkan No. Panggil Buku">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control uppercase-text" placeholder="Masukkan No. Panggil Buku">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Muka Surat</label>
                        <input type="number" name="mukasurat_buku" class="form-control uppercase-text" placeholder="Masukkan No. Panggil Buku">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. ISBN (International Book Serial Number)</label>
                        <input type="number" name="isbn" class="form-control uppercase-text" placeholder="Masukkan No. ISBN">
                    </div>
                </div>
                <div class="col-md">
                <div class="mb-3">
                        <label class="form-label">Jenis Buku</label>
                        <select id="jenisBuku" name="id_jenisBuku" class="form-control" required>
                            <option value="-">Pilih Jenis Buku :-</option>
                            <?php foreach ($jenisBuku as $jenis): ?>
                                <option value="<?php echo $jenis['id_jenisBuku']; ?>" 
                                    data-jenis="<?php echo htmlspecialchars($jenis['jenisBuku']); ?>">
                                    <?php echo $jenis['jenisBuku']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori Buku</label>
                        <select id="kategoriBuku" name="id_kategoriBuku" class="form-control" required disabled>
                            <option value="-">Pilih Jenis Buku Dahulu :-</option>
                            <?php foreach ($kategoriBuku as $kategori): ?>
                                <option value="<?php echo $kategori['id_kategoriBuku']; ?>" 
                                    data-kategori="<?php echo htmlspecialchars($kategori['kategoriBuku']); ?>">
                                    <?php echo $kategori['kategoriBuku']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Perolehan Buku</label>
                        <input type="text" name="noPerolehan_buku" class="form-control uppercase-text" placeholder="Masukkan No. Perolehan Buku">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Panggil Buku</label>
                        <input type="text" name="noPanggil_buku" required class="form-control uppercase-text" placeholder="Masukkan No. Panggil Buku">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Buku</label>
                        <input type="text" name="lokasi_buku" class="form-control uppercase-text" placeholder="Masukkan Lokasi Buku">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bahasa Buku</label>
                        <select name="bahasa_buku" class="form-control">
                            <option value="">Pilih Bahasa Buku :-</option>
                            <option value="BM">BAHASA MELAYU</option>
                            <option value="BI">BAHASA INGGERIS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Buku</label>
                        <input type="file" name="gambar_buku" class="form-control-file">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" hidden>Kuantiti Buku</label>
                        <input type="number" name="kuantiti_buku" class="form-control-file" value="1" hidden>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="books.php"><i class='bx bx-arrow-back'></i>&nbspKembali</a>
                        <button type="submit" name="add" class="btn btn-custom"><i class='bx bxs-book-add'></i>&nbspTambah Buku</button>
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
            $('#addForm').submit(function() {
                $('input.uppercase-text').each(function() {
                    $(this).val($(this).val().toUpperCase());
                });
            });
        });
    </script>

    <script>
        document.getElementById('jenisBuku').addEventListener('change', function () {
            const jenisBuku = this.options[this.selectedIndex].getAttribute('data-jenis');
            const kategoriBuku = document.getElementById('kategoriBuku');

            // Enable dropdown
            kategoriBuku.disabled = false;

            // Clear existing options
            Array.from(kategoriBuku.options).forEach(option => {
                option.style.display = 'none'; // Hide all options initially
                option.selected = false; // Deselect all options
            });

            // Akademik and Vokasional categories
            const akademik = [
                'SEJARAH',
                'PENGAJIAN ISLAM',
                'BAHASA MELAYU',
                'BAHASA INGGERIS',
                'MATEMATIK',
                'SAINS',
                'UMUM',
            ];

            const vokasional = [
                'KULINARI',
                'BAKERI',
                'PEMASARAN',
                'PERAKAUNAN',
                'ANIMASI',
                'WEB',
                'PTA',
            ];

            // Set matching category
            let matchCategory = '';
            if (akademik.includes(jenisBuku)) {
                matchCategory = 'AKADEMIK';
            } else if (vokasional.includes(jenisBuku)) {
                matchCategory = 'VOKASIONAL';
            }

            // Show only matching options
            Array.from(kategoriBuku.options).forEach(option => {
                if (option.getAttribute('data-kategori') === matchCategory) {
                    option.style.display = 'block'; // Show matching options
                    option.selected = true; // Select the first matching option
                }
            });
        });

        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

            if (getURL == 'exists') {
                Swal.fire({
                    icon: 'error',
                    title: 'Penambahan Buku Gagal!',
                    text: 'Data buku ini telah wujud, sila cuba yang lain.'
                }).then(() => {
                    // Remove the status=error parameter from the URL
                    const urlWithoutStatus = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, urlWithoutStatus);
                });
            }

            else if (getURL == 'addedError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Penambahan Buku Gagal!',
                    text: 'Data buku tidak berjaya ditambah.'
                }).then(() => {
                    // Remove the status=error parameter from the URL
                    const urlWithoutStatus = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, urlWithoutStatus);
                });
            } 

    </script>

</body>
</html>
