<?php 
include 'database/config.php';
include 'session/security.php';

$page = 'PIF Centre | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">

    <!--==============AOS CSS================-->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <title><?php echo $page; ?></title>
</head>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: "Quicksand";
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .center-form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .signup-form {
            background-color: #fff;
            padding: 2rem;
            max-width: 60%; /* Increased width */
            width: 100%;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .signup-form img {
            width: 90px;
            display: block;
            margin: 0 auto;
        }

        .btn-register {
            background-color: var(--cedar);
            color: white;
            border-radius: 0;
            margin-top: 20px;
            width: 100%;
        }

        .btn-register:hover {
            background-color: transparent;
            color: var(--cedar);
            border: 1px solid var(--cedar);
        }

        /* Close button styles */
        .close-btn {
            background-color: var(--red);
            color: white;
            border-radius: 0;
            width: 100%;
        }

        .close-btn:hover {
            background-color: transparent;
            color: var(--red);
            border: 1px solid var(--red);
        }

        .form-title {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 0;
            border: 1px solid #ccc;
            margin-bottom: 1rem;
        }

        input:focus, select:focus {
            border-color: var(--cedar);
            box-shadow: 0 0 5px var(--cedar), 0 0 10px var(--cedar);
            outline: none; 
        }

        @media (max-width: 768px) {
            .signup-form {
                max-width: 90%; /* Adjust for smaller screens */
                padding: 1.5rem;
            }
        }
    </style>
<body>

<div class="center-form w-100">
    <div class="signup-form">
        <div data-aos="fade-down">
            <img src="images/pif-logo.png" alt="Library Logo">
            <h5 class="text-center">PERPUSTAKAAN IBNU FIRNAS</h5>
            <br>
            <h5 class="text-center mb-5"><b>DAFTAR AKAUN PENGGUNA</b></h5>
            
            <form method="post" action="backend/signup.php" id="signupForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email"><b>Email:</b></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_penuh"><b>Nama Penuh:</b></label>
                            <input type="text" name="nama_penuh" id="nama_penuh" class="form-control" placeholder="Masukkan Nama Penuh" required>
                        </div>
                        <div class="form-group">
                            <label for="nokp"><b>No. Kad Pengenalan:</b></label>
                            <input type="text" name="nokp" id="nokp" class="form-control" placeholder="Masukkan No. Kad Pengenalan" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password"><b>Katalaluan:</b></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Katalaluan" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password"><b>Pengesahan Katalaluan</b></label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control" placeholder="Masukkan Pengenalan Katalaluan" required>
                        </div>
                        <div class="form-group">
                            <label for="jawatan"><b>Jawatan:</b></label>
                            <select name="jawatan" id="jawatan" class="form-control" onchange="toggleStudentFields(this.value)" required>
                                <option value="">-- Pilih Jawatan --</option>
                                <option value="PELAJAR">PELAJAR</option>
                                <option value="PENSYARAH">PENSYARAH</option>
                                <option value="PEKERJA">PEKERJA</option>
                                <option value="LAIN-LAIN">LAIN-LAIN</option>
                            </select>
                        </div>
                </div>
                <div id="studentFields" style="display: none;">
                    <div class="form-group">
                        <label for="tahun"><b>Tahun:</b></label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">-- Pilih Tahun --</option>
                            <option value="1 SVM">1 SVM</option>
                            <option value="2 SVM">2 SVM</option>
                            <option value="1 DVM">1 DVM</option>
                            <option value="2 DVM">2 DVM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="program"><b>Program:</b></label>
                        <select name="program" id="program" class="form-control">
                            <option value="">-- Pilih Program --</option>
                            <option value="TEKNOLOGI KOMPUTERAN">TEKNOLOGI KOMPUTERAN</option>
                            <option value="ANIMASI 3D">ANIMASI 3D</option>
                            <option value="PEMASARAN">PEMASARAN</option>
                            <option value="PERAKAUNAN">PERAKAUNAN</option>
                            <option value="SENI KULINARI">SENI KULINARI</option>
                            <option value="BAKERI & PASTRI">BAKERI & PASTRI</option>
                            <option value="PERABOT">SLDN PERABOT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="matriks"><b>No. Matriks:</b></label>
                        <input type="text" name="matriks" id="matriks" class="form-control" placeholder="Masukkan No. Matriks">
                    </div>
                </div>
                <button type="submit" class="btn btn-register">DAFTAR MASUK</button>
                <button class="btn close-btn" id="closeBtn">KEMBALI KE HALAMAN LOG MASUK</button>
            </form>
        </div>
    </div>
</div>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000, // Animation duration
        once: true      // Whether animation should happen only once
    });

    document.getElementById('closeBtn').addEventListener('click', function() {
        window.location.href = 'index.php'; // Redirect to login page
    });
</script>

<script>
    document.getElementById('closeBtn').addEventListener('click', function() {
        window.location.href = 'login-page.php'; // Redirect to login page
    });

    function toggleStudentFields(jawatan) {
        const studentFields = document.getElementById('studentFields');
        if (jawatan === 'PELAJAR') {
            studentFields.style.display = 'block';
        } else {
            studentFields.style.display = 'none';
        }
    }
</script>
</body>
</html>
