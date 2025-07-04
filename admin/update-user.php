<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database/config.php';

$nokp = isset($_GET['nokp']) ? $_GET['nokp'] : '';

if (empty($nokp)) {
    header("Location: users.php?status=missingNokp");
    exit();
}

// Fetch user information
$stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: users.php?status=userNotFound");
    exit();
}

$page = 'Kemaskini Data Pengguna | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
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
        <h1>Kemaskini Maklumat Pengguna</h1>
        <div class="hr">
            <hr>
        </div>
        <form id="editForm" action="backend/edit-user.php" method="post">
            <input type="hidden" name="nokp" value="<?php echo htmlspecialchars($user['nokp']); ?>">
            <div class="row justify-content-center">
                <div class="col-md">
                    <div class="mb-3">
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" name="nama_penuh" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($user['nama_penuh']); ?>" placeholder="Masukkan Nama Penuh Pengguna" oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Kad Pengenalan (<b>Tidak Boleh Diubah</b>)</label>
                        <input type="text" name="nokp" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($user['nokp']); ?>" placeholder="Masukkan No. Kad Pengenalan (tanpa -)" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telefon (<b>Tidak Perlu (-)</b>)</label>
                        <input type="text" name="notel" required class="form-control uppercase-text" value="<?php echo htmlspecialchars($user['notel']); ?>" placeholder="Masukkan No. Telefon (tanpa -)">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Laluan</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan Kata Laluan">
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Mengesahkan Kata Laluan</label>
                        <input type="password" class="form-control" name="confirm-password" placeholder="Masukkan Pengesahan Kata Laluan">
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
                        <input type="text" name="email" required class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Masukkan Email Pengguna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="jawatan" class="form-control" id="jawatan" required>
                            <option value="PELAJAR" <?php echo $user['jawatan'] === 'PELAJAR' ? 'selected' : ''; ?>>PELAJAR</option>
                            <option value="PENSYARAH" <?php echo $user['jawatan'] === 'PENSYARAH' ? 'selected' : ''; ?>>PENSYARAH</option>
                            <option value="PEKERJA" <?php echo $user['jawatan'] === 'PEKERJA' ? 'selected' : ''; ?>>PEKERJA</option>
                            <option value="LAIN-LAIN" <?php echo $user['jawatan'] === 'LAIN-LAIN' ? 'selected' : ''; ?>>LAIN-LAIN</option>
                        </select>
                    </div>
                    <div id="pelajar-info" class="<?php echo $user['jawatan'] === 'PELAJAR' ? '' : 'hidden'; ?>">         
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                                <select name="tahun" class="form-control" id="tahun">
                                    <option value="">Pilih Tahun :-</option>
                                    <option value="1 SVM" <?php echo $user['tahun'] === '1 SVM' ? 'selected' : ''; ?>>1 SVM</option>
                                    <option value="2 SVM" <?php echo $user['tahun'] === '2 SVM' ? 'selected' : ''; ?>>2 SVM</option>
                                    <option value="1 DVM" <?php echo $user['tahun'] === '1 DVM' ? 'selected' : ''; ?>>1 DVM</option>
                                    <option value="2 DVM" <?php echo $user['tahun'] === '2 DVM' ? 'selected' : ''; ?>>2 DVM</option>
                                </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program</label>
                                <select name="program" class="form-control" id="program">
                                    <option value="">Pilih Program :-</option>
                                    <option value="TEKNOLOGI KOMPUTERAN" <?php echo $user['program'] === 'TEKNOLOGI KOMPUTERAN' ? 'selected' : ''; ?>>TEKNOLOGI KOMPUTERAN</option>
                                    <option value="ANIMASI 3D" <?php echo $user['program'] === 'ANIMASI 3D' ? 'selected' : ''; ?>>ANIMASI 3D</option>
                                    <option value="PEMASARAN" <?php echo $user['program'] === 'PEMASARAN' ? 'selected' : ''; ?>>PEMASARAN</option>
                                    <option value="PERAKAUNAN" <?php echo $user['program'] === 'PERAKAUNAN' ? 'selected' : ''; ?>>PERAKAUNAN</option>
                                    <option value="SENI KULINARI" <?php echo $user['program'] === 'SENI KULINARI' ? 'selected' : ''; ?>>SENI KULINARI</option>
                                    <option value="BAKERI & PASTRI" <?php echo $user['program'] === 'BAKERI & PASTRI' ? 'selected' : ''; ?>>BAKERI & PASTRI</option>
                                    <option value="PERABOT" <?php echo $user['program'] === 'PERABOT' ? 'selected' : ''; ?>>SLDN PERABOT</option>
                                </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Matriks</label>
                            <input type="text" name="matriks" class="form-control uppercase-text" value="<?php echo htmlspecialchars($user['matriks']); ?>" placeholder="Masukkan No. Matriks Pelajar" oninput="this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md">
                    <br>
                    <div class="mb-3">
                        <a class="btn btn-secondary" href="users.php"><i class='bx bx-arrow-back'></i>&nbspKembali</a>
                        <button type="submit" name="update" class="btn btn-custom"><i class='bx bxs-user-check'></i>&nbspKemaskini</button>
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
    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
        $('#jawatan').change(function() {
            var jawatan = $(this).val();
            if (jawatan === 'PELAJAR') {
                $('#pelajar-info').show();
            } else {
                $('#pelajar-info').hide();
            }
        });

        // Convert text inputs to uppercase
        $('input.uppercase-text').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        $('#editForm').submit(function(event) {
            // Check if passwords match
            var password = $('input[name=password]').val();
            var confirmPassword = $('input[name=confirm-password]').val();
            
            if (password && password !== confirmPassword) {
                event.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Kemaskini Gagal!',
                    text: 'Pengesahan Password Tidak Sama.'
                });
                return;
            }

            // Convert text inputs to uppercase (in case not handled by oninput)
            $('input.uppercase-text').each(function() {
                $(this).val($(this).val().toUpperCase());
            });
        });
    });
    </script>

</body>
</html>
