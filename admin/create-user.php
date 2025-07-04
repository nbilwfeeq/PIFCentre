<?php 
    include 'database/config.php';
    include 'session/security.php';
    $page = 'Tambah Data Pengguna | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
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
        <h1>Tambah Maklumat Pengguna</h1>
        <div class="hr">
            <hr>
        </div>
        <form id="addForm" action="backend/add-user.php" method="post">
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" name="nama_penuh" required class="form-control uppercase-text" placeholder="Masukkan Nama Penuh Pengguna" oninput="toUpperCase(this)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Kad Pengenalan (<b>Tidak Boleh Diubah</b>)</label>
                        <input type="text" name="nokp" required class="form-control uppercase-text" placeholder="Masukkan No. Kad Pengenalan (tanpa -)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telefon (<b>Tidak Perlu (-)</b>)</label>
                        <input type="text" name="notel" required class="form-control uppercase-text" placeholder="Masukkan No. Telefon (tanpa -)">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Laluan</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan Kata Laluan" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Mengesahkan Kata Laluan</label>
                        <input type="password" class="form-control" name="confirm-password" placeholder="Masukkan Pengesahan Kata Laluan" required>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPass"> Tunjuk kata laluan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" required class="form-control" placeholder="Masukkan Email Pengguna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="jawatan" class="form-control" id="jawatan" required>
                            <option value="">Pilih Kategori :-</option>
                            <option value="PELAJAR">PELAJAR</option>
                            <option value="PENSYARAH">PENSYARAH</option>
                            <option value="PEKERJA">PEKERJA</option>
                            <option value="LAIN-LAIN">LAIN-LAIN</option>
                        </select>
                    </div>
                    <div id="pelajar-info" class="hidden">
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun">
                                <option value="">Pilih Tahun :-</option>
                                <option value="1 SVM">1 SVM</option>
                                <option value="2 SVM">2 SVM</option>
                                <option value="1 DVM">1 DVM</option>
                                <option value="2 DVM">2 DVM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                            <select class="form-control" id="program" name="program">
                                <option value="">Pilih Program :-</option>
                                <option value="TEKNOLOGI KOMPUTERAN">TEKNOLOGI KOMPUTERAN</option>
                                <option value="ANIMASI 3D">ANIMASI 3D</option>
                                <option value="PEMASARAN">PEMASARAN</option>
                                <option value="PERAKAUNAN">PERAKAUNAN</option>
                                <option value="SENI KULINARI">SENI KULINARI</option>
                                <option value="BAKERI & PASTRI">BAKERI & PASTRI</option>
                                <option value="PERABOT">SLDN PERABOT</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Matriks<b>(Contoh: BKV0423KA001)</b></label>
                            <input type="text" name="matriks" class="form-control uppercase-text" placeholder="Masukkan No. Matriks Pelajar" oninput="toUpperCase(this)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md">
                    <br>
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="users.php"><i class='bx bx-arrow-back'></i>&nbspKembali</a>
                        <button type="submit" name="add" class="btn btn-custom"><i class='bx bxs-user-plus'></i>&nbspDaftar Pengguna</button>
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
        // Function to convert text to uppercase
        function toUpperCase(element) {
            element.value = element.value.toUpperCase();
        }

        // Show or hide password
        $('#showPass').click(function() {
            var pass = $('input[name=password]');
            var confirmPass = $('input[name=confirm-password]');

            if (pass.attr('type') == 'text') {
                pass.attr('type', 'password');
                confirmPass.attr('type', 'password');
            } else {
                pass.attr('type', 'text');
                confirmPass.attr('type', 'text');
            }
        });

        $(document).ready(function() {
            // Initially hide the sections
            $('#pelajar-info').hide();
            $('#other-info').hide();

            // Toggle visibility of fields based on jawatan selection
            $('#jawatan').change(function() {
                var jawatan = $(this).val();
                if (jawatan === 'PELAJAR') {
                    $('#pelajar-info').show();
                    $('#other-info').hide();
                } else {
                    $('#pelajar-info').hide();
                    $('#other-info').show();
                }
            });

            // Convert text inputs to uppercase on form submission
            $('#addForm').submit(function(event) {
                // Check if passwords match
                var password = $('input[name=password]').val();
                var confirmPassword = $('input[name=confirm-password]').val();
                
                if (password !== confirmPassword) {
                    event.preventDefault(); // Prevent form submission
                    Swal.fire({
                        icon: 'error',
                        title: 'Pendaftaran Gagal!',
                        text: 'Pengesahan Password Tidak Sama.'
                    });
                    return;
                }

                // Convert text inputs to uppercase
                $('input.uppercase-text').each(function() {
                    $(this).val($(this).val().toUpperCase());
                });
            });

            const parameter = new URLSearchParams(window.location.search);
            const getURL = parameter.get('status');

            if (getURL == 'missingFields') {
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftar Gagal!',
                    text: 'Akaun pengguna ini adalah berjawatan pelajar, sila isi program dan no matriks.'
                }).then(() => {
                    // Remove the status=error parameter from the URL
                    const urlWithoutStatus = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, urlWithoutStatus);
                });
            } 
            else if (getURL == 'exists') {
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: 'Akaun pengguna ini telah wujud, sila cuba akaun yang lain.'
                }).then(() => {
                    // Remove the status=error parameter from the URL
                    const urlWithoutStatus = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, urlWithoutStatus);
                });
            }
            else if (getURL == 'registerError') {
                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: 'Akaun pengguna tidak berjaya ditambah.'
                }).then(() => {
                    // Remove the status=error parameter from the URL
                    const urlWithoutStatus = window.location.href.split('?')[0];
                    window.history.replaceState({}, document.title, urlWithoutStatus);
                });
            }
        });
    </script>

</body>

</html>

