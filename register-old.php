<?php 
    include 'database/config.php';
    include 'session/security.php';
    $page = 'Ibnu Firnas Knowledge Centre | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';
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

    <!-- ==================CSS===================== -->
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
        body {
            font-family: "Quicksand";
            color: #333;
            background-color: #f8f9fa;
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            }
        .form-container {
            max-width: 500px;
            max-height: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #e0e0e0;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            display: flex; /* Add this */
            flex-direction: column; /* Align items vertically */
            align-items: center; /* Center items horizontally */
        }
        .form-container img {
            width: 100px;
            display: block;
            margin: 0 auto;
        }
        .form-container h1 {
            font-size: 24px;
            font-weight: bold;
            color: var(--cedar);
            text-align: center;
        }
        .form-container p {
            font-size: 1rem;
            color: #333;
            text-align: center;
        }
        .form-group {
            width: 100%; /* Ensure it takes full width within the container */
            height: 100%;
            max-width: 700px; /* Limit the width */
            max-height: 700px;
            margin: 0px auto; /* Center with automatic margins */
            text-align: left; /* Align text left within the group */
        }
        .form-group label {
            font-weight: normal;
            font-size: 14px;
        }
        .form-control {
            border-radius: 0;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
        }
        .form-check-label {
            font-weight: normal;
            font-size: 14px;
        }

        .button-container {
            display: flex; /* Use flexbox for alignment */
            justify-content: space-between; /* Add space between the buttons */
            gap: 0px; /* Optional: Add some spacing between buttons */
            margin-top: 10px; /* Add spacing above the buttons if needed */
            margin-bottom: 20px;
        }
        /* Button style */
        .btn-custom {
            background-color: var(--cedar); 
            color: white; 
            border: none; 
            border-radius: 0; 
            padding: 12px 20px; 
            font-size: 1rem; 
            width: 100%; 
            transition: all 0.4s ease; 
        }

        .btn-custom:hover {
            background-color: white;
            color: var(--cedar); 
            border: 2px solid var(--cedar); 
            transform: scale(1.02); 
        }

        /* Button style */
        .btn-return {
            background-color: var(--red); 
            color: white; 
            border: none; 
            border-radius: 0; 
            padding: 12px 20px; 
            font-size: 1rem; 
            width: 100%; 
            transition: all 0.4s ease; 
        }

        .btn-return:hover {
            background-color: white;
            color: var(--red); 
            border: 2px solid var(--red); 
            transform: scale(1.02); 
        }

        .hidden {
            display: none;
        }

        .uppercase-text {
            text-transform: uppercase; /* Transform user input to uppercase */
        }

        .uppercase-text::-webkit-input-placeholder {
            text-transform: none; /* Chrome, Safari, Edge */
        }

        .uppercase-text:-moz-placeholder {
            text-transform: none; /* Firefox 18- */
        }

        .uppercase-text::-moz-placeholder {
            text-transform: none; /* Firefox 19+ */
        }

        .uppercase-text:-ms-input-placeholder {
            text-transform: none; /* Internet Explorer 10+ */
        }

        .form-control-file {
            width: 95px;
            border: none;
            margin-right: 10px;
        }

        .form-control-file:focus {
            border-color: none; 
            box-shadow: none;
            outline: none; 
        }

        /* Close button styles */
        .close-btn {
            margin-left: 60vh;
            font-size: 40px;
            color: #333;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: 0.4s ease;
        }

        .close-btn:hover {
            color: #FF0000;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 0;
            border: 1px solid #ccc;
            transition: 0.4s ease;
        }

        input:focus {
            border-color: var(--cedar); 
            box-shadow: 0 0 5px var(--cedar), 0 0 10px var(--cedar);
            outline: none; 
        }

        select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 0;
            border: 1px solid #ccc;
            transition: 0.4s ease;
        }

        select:focus {
            border-color: var(--cedar); 
            box-shadow: 0 0 5px var(--cedar), 0 0 10px var(--cedar);
            outline: none; 
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 5px #dc3545;
        }
        
  </style>

</head>
<body>

<?php include 'includes/loader.php'; ?>

<!-- Background Video -->
<!-- <video autoplay loop muted>
    <source src="images/bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video> -->

<div class="form-container" data-aos="fade-down">

    <button class="close-btn" id="closeBtn"><i class="bx bx-x"></i></button>

    <!-- Unified Form for All Steps -->
    <form id="mainForm" action="backend/signup.php" method="post" enctype="multipart/form-data">

        <!-- Form Section: Section 1 -->
        <div id="step1">
        <input type="hidden" id="gambarUser" name="gambarUser" class="form-control-file" onchange="previewProfilePic(event)" value="default-profile.png">
            <img src="images/pif-logo.png" alt="">
            <br>
            <h3><b>Daftar Akaun : </b></h3>
            <br>
            <div class="form-group">
                <label for="email">*Email:</label>
                <input type="email"  id="email" name="email" placeholder="Masukkan Email Anda..." required>
            </div>
            <br>
            <div class="form-group">
                <label for="nama_penuh">*Nama Penuh:</label>
                <input type="text" class=" uppercase-text" id="nama_penuh" name="nama_penuh" placeholder="Masukkan Nama Penuh Anda..." required>
            </div>
            <br><br>
            <div class="button-container">
                <button type="button" onclick="showNextStep(2)" class="btn btn-custom">SETERUSNYA</button>
            </div>
        </div>

         <!-- Form Section: Section 2 -->
        <div id="step2" class="hidden">
            <h3><b>Maklumat Utama : </b></h3>
            <br>
            <div class="form-group">
                <label for="nokp">*No. Kad Pengenalan <b>Tanpa (-)</b></label>
                <input type="text"  id="nokp" name="nokp" placeholder="Masukkan No. Kad Pengenalan Anda..." required>
            </div>
            <br>
            <div class="form-group">
                <label for="notel">*No. Telefon: <b>Tanpa (-)</b></label>
                <input type="text"  id="notel" name="notel" placeholder="Masukkan No. Telefon Anda..." required>
            </div>
            <br>
            <div class="form-group">
            <label for="jawatan">*Kategori:</label>
            <select id="jawatan" name="jawatan" required>
                <option value="">Pilih Kategori :-</option>
                <option value="PELAJAR">PELAJAR</option>
                <option value="PENSYARAH">PENSYARAH</option>
                <option value="PEKERJA">PEKERJA</option>
                <option value="LAIN-LAIN">LAIN-LAIN</option>
            </select>
        </div>
        <br>
            <div id="pelajar-info" class="hidden">
                <div class="form-group">
                    <label for="tahun">*Tahun:</label>
                        <select id="tahun" name="tahun">
                            <option value="">Pilih Tahun :-</option>
                            <option value="1 SVM">1 SVM</option>
                            <option value="2 SVM">2 SVM</option>
                            <option value="1 DVM">1 DVM</option>
                            <option value="2 DVM">2 DVM</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="program">*Program:</label>
                    <select id="program" name="program">
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
                <div class="form-group">
                    <label for="matriks">*No. Matriks: <b>(Contoh: BKV0423KA001)</b></label>
                    <input type="text" class=" uppercase-text" name="matriks" placeholder="Masukkan No. Matriks...">
                </div>
                <br>
            </div>
            <br>
            <div class="button-container">
                <button type="button" onclick="showPreviousStep(1)" class="btn btn-return">KEMBALI</button>
                <button type="button" onclick="showNextStep(3)" class="btn btn-custom">SETERUSNYA</button>
            </div>
        </div>

        <!-- Form Section: Section 3 -->
        <div id="step3" class="hidden">
            <h3><b>Katalaluan : </b></h3>
            <br>
            <div class="form-group">
                <label for="password">*Kata Laluan:</label>
                <input type="password"  name="password" placeholder="Masukkan Kata Laluan" required>
            </div>
            <br>
            <div class="form-group">
                <label for="confirm-password">*Mengesahkan Kata Laluan:</label>
                <input type="password"  name="confirm-password" placeholder="Masukkan Pengesahan Kata Laluan" required>
                <br><br>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="showPass"> Tunjuk kata laluan
                    </div>
                </div>
            </div>
            <br><br>
            <div class="button-container">
                <button type="button" onclick="showPreviousStep(2)" class="btn btn-return">KEMBALI</button>
                <button type="submit" id="btnRegister" name="register" class="btn btn-custom">DAFTAR AKAUN</button>
            </div>
        </div>

    </form>
    
</div>

<!--=============== Bootstrap 5.2 ===============-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!--=============== jQuery ===============-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!--=============== Datatables ===============-->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<!--=============== SweetAlert2 ===============-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--==================== AOS =======================-->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 1000, // Animation duration
        once: true      // Whether animation should happen only once
    });
</script>

<!-- Form Paging -->
<script>

    function validateStep(step) {
        const stepElement = document.getElementById(`step${step}`);
        const inputs = stepElement.querySelectorAll("input[required], select[required]");
        let isValid = true;

        inputs.forEach((input) => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add("is-invalid"); // Add invalid style
                input.focus();
            } else {
                input.classList.remove("is-invalid");
            }
        });

        return isValid;
    }

    function showNextStep(step) {
        const currentStep = step - 1;
        if (validateStep(currentStep)) {
            document.getElementById(`step${currentStep}`).classList.add("hidden");
            document.getElementById(`step${step}`).classList.remove("hidden");
            updateSidebar(step);
        } else {
            Swal.fire({
                icon: "warning",
                title: "Maklumat Tidak Lengkap!",
                text: "Sila isi semua ruangan yang diperlukan sebelum meneruskan."
            });
        }
    }

    function showPreviousStep(step) {
        document.getElementById(`step${step + 1}`).classList.add("hidden");
        document.getElementById(`step${step}`).classList.remove("hidden");
        updateSidebar(step);
    }


    function updateSidebar(step) {
        for (let i = 1; i <= 3; i++) {
            const indicator = document.getElementById(`step${i}-indicator`).children[0];
            if (i < step) {
                indicator.classList.replace("bg-gray-300", "bg-[#458A76]");
                indicator.innerHTML = '✓';
            } else if (i === step) {
                indicator.classList.replace("bg-gray-300", "bg-[#458A76]");
            } else {
                indicator.classList.replace("bg-[#458A76]", "bg-gray-300");
                indicator.innerHTML = i;
            }
        }
    }
</script>

<script>
    document.getElementById('closeBtn').addEventListener('click', function() {
        window.location.href = 'login-page.php'; // Redirect to login page
    });

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

    // Toggle visibility of fields based on jawatan selection
    $('#jawatan').change(function() {
        var jawatan = $(this).val();
        if (jawatan === 'PELAJAR') {
            $('#pelajar-info').removeClass('hidden');
            $('#other-info').addClass('hidden');
        } else {
            $('#pelajar-info').addClass('hidden');
            $('#other-info').removeClass('hidden');
        }
    });

    // Convert text inputs to uppercase on form submission
    $('#registerForm').submit(function(event) {
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

    // Preview profile picture
    function previewProfilePic(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('profilePicPreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Reset profile picture to default
    document.getElementById('resetPic').addEventListener('click', function() {
        var defaultPic = 'images/profile-pic/default-profile.png';
        document.getElementById('profilePicPreview').src = defaultPic;
        document.getElementById('gambarUser').value = '';
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

    else if (getURL == 'fileUploadError') {
        Swal.fire({
            icon: 'error',
            title: 'Upload Gambar Gagal!',
            text: 'Cuba gambar yang berbeza atau cuba sekali lagi.'
        }).then(() => {
            // Remove the status=error parameter from the URL
            const urlWithoutStatus = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, urlWithoutStatus);
        });
    } 
    
    else if (getURL == 'passwordError') {
        Swal.fire({
            icon: 'error',
            title: 'Pendaftaran Gagal!',
            text: 'Pengesahan Password Tidak Sama.'
        }).then(() => {
            // Remove the status=error parameter from the URL
            const urlWithoutStatus = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, urlWithoutStatus);
        });
    }

    // Redirect to login page (login-page.php)
    document.getElementById('btnReturn').addEventListener('click', function() {
        window.location.href = 'login-page.php';
    });
</script>

</body>
</html>