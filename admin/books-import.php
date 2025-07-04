<?php 
    include 'database/config.php';
    $page = 'Data Buku | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';

    // get user info
    $stmt = $connect->prepare("SELECT * FROM user");
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_all(MYSQLI_ASSOC);

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

    // Get bahasa buku info (if stored separately in a table)
    $bahasaStmt = $connect->prepare("SELECT DISTINCT bahasa_buku FROM books");
    $bahasaStmt->execute();
    $bahasaResult = $bahasaStmt->get_result();
    $bahasaBuku = $bahasaResult->fetch_all(MYSQLI_ASSOC);
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <title><?php echo $page; ?></title>
</head>
<body>

    <?php include('includes/topbar.php'); ?>

    <div class="content container p-3">
    <h1>Muat Naik ke Pangkalan Data Buku</h1>
        <div class="hr">
            <hr>
        </div>
    
        <div class='text-bg-danger p-3'>
            <h5 class='text-white'><b>Sila Ambil Perhatian !</b></h5>
            <ol>
                <li>Muat turun <em>template</em> yang disediakan terlebih dahulu <b style="color:white;"><a style="text-decoration: none; color:white;" href="template/template.csv">Di Sini</a></b>.</li>
                <li>Berikut merupakan jenis dan kategori buku (id_jenisBuku) dan (id_kategoriBuku) yang perlu di isi di dalam template
                <div class="row">    
                    <div class="col-md-3">
                        <br>
                        <p><b>Jenis Buku</b></p>
                        <p>"1" => 'SEJARAH'</p>
                        <p>"2" => 'PENGAJIAN ISLAM'</p>
                        <p>"3" => 'BAHASA MELAYU'</p>
                        <p>"4" => 'BAHASA INGGERIS'</p>
                        <p>"5" => 'MATEMATIK'</p>
                        <p>"6" => 'SAINS'</p>
                        <p>"7" => 'UMUM'</p>
                        <p>"8" => 'KULINARI'</p>
                        <p>"9" => 'BAKERI'</p>
                        <p>"10" => 'PEMASARAN'</p>
                        <p>"11" => 'PERAKAUANAN'</p>
                        <p>"12" => 'ANIMASI'</p>
                        <p>"13" => 'WEB'</p>
                        <p>"14" => 'PTA'</p>
                    </div>
                    <div class="col-md-3">
                        <br>
                        <p><b>Kategori Buku</b></p>
                        <p>"1" => 'VOKASIONAL'</p>
                        <p>"2" => 'AKADEMIK'</p>
                    </div>
                </div>
                </li>
                <li>PASTIKAN di fail tidak perlu colum 'No' , 'Subjek' dan 'Bahan'</li>
                <br>
                <li>PASTIKAN fail disimpan di dalam format <b>.csv (Comma Delimited)</b></li>
            </ol>
        </div>
        <div class="row justify-content-center">
            <div class="col p-3">
                <form class="p-3" action="backend/import.php" method="post" name="upload_excel" enctype="multipart/form-data">
                    <h5 class="text-center">Muat Naik Di Sini</h5>
                    <input type="file" name="file" id="file" class="form-control" accept=".csv" value="Klik Untuk Muat Naik Excel" required>
                    <input type="hidden" name="gambar_buku" value="images/default-book.png">
                    <input type="hidden" name="kuantiti_buku" value="1">
                    <br>
                    <button type="submit" name="Import" class="btn btn-primary"><i class='bx bx-upload'></i> Muat Naik</button>
                </form>
            </div>
        </div>
        
    </div>

    <?php include'includes/footer.php' ?>

    <script>
        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');
        
        if (getURL == 'imported') {
        Swal.fire({
            icon: 'success',
            title: 'Berjaya!',
            text: 'Data buku berjaya dimuat naik!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: '#28a745',
            iconColor: '#fff',
            color: '#fff',
        }).then(() => {
            const urlWithoutStatus = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, urlWithoutStatus);
        });
    }
    </script>

</body>
</html>