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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <title><?php echo $page; ?></title>
</head>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: "Quicksand";
            overflow: hidden;
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container-fluid {
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .row {
            margin: 0;
            width: 100%;
        }

        /* Left-side full-screen image */
        .left-side {
            padding: 0;
            height: 110vh;
            overflow: hidden;
        }

        .left-side img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            margin-top: 10vh;
        }

        /* Right-side form styling */
        .right-side {
            margin-top: 15vh;
            margin-left: 20vh;
            background-color: #fff;
            padding: 2rem;
            max-width: 450px;
            width: 100%;
            height: 90vh;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .login-form {
            width: 100%;
        }

        .login-form img {
            width: 90px;
            display: block;
            margin: 0 auto;
        }

        .btn-login {
            background-color: var(--cedar);
            color: white;
            border-radius: 0;
            width: 53vh;
        }

        .btn-login:hover {
            background-color: white;
            color: var(--cedar);
            border: 1px solid var(--cedar);
        }

        .btn-custom {
            background-color: var(--cedar);
            color: white;
            border-radius: 0;
            width: 100%;
        }

        .btn-custom:hover {
            background-color: white;
            color: var(--cedar);
            border: 1px solid var(--cedar);
        }

        .form-title {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
        }

        .sign-up {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .sign-up a {
            color: red;
        }

        .forgot-password {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .forgot-password button {
            color: black;
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

        .checkbox {
            margin-top: 10px;
        }

        /* Close button styles */
        .close-btn {
            margin-top: -10vh;
            margin-left: 48vh;
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


        /* Responsive adjustments for mobile */
        @media screen and (max-width: 767px) {

            .close-btn {
                margin-top: -10vh;
                margin-left: 34vh;
                font-size: 40px;
                color: #333;
                background: transparent;
                border: none;
                cursor: pointer;
                transition: 0.4s ease;
            }

            .container-fluid {
                display: block;
                height: auto;
                padding: 0;
            }

            .left-side {
                display: block;
                height: auto;
            }

            .left-side img {
                display: none;
            }

            .right-side {
                margin: 2rem auto;
                padding: 1rem;
                width: 90%;
                height: auto;
                box-shadow: none;
            }

            .right-side .login-form {
                margin-top: 1rem;
            }

            .btn-login {
                width: 100%;
            }

            .return {
                bottom: 20px;
                right: 20px;
            }
        }

    </style>
<body>

    <?php include 'includes/loader.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Image banner -->
            <div class="col-md-6 left-side d-md-block">
                <img src="images/banner.png" alt="Background Image" class="img-fluid">
            </div>

            <!-- Login form -->
            <div class="col-md-6 right-side" data-aos="fade-down">
                <div class="login-form">
                    <button class="close-btn" id="closeBtn"><i class="bx bx-x"></i></button>
                    <img src="images/pif-logo.png" alt="Library Logo">
                    <h5 class="text-center">PERPUSTAKAAN IBNU FIRNAS</h5>
                    <p class="form-title">
                        Kolej Vokasional Kuala Selangor, <br>Kementerian Pendidikan Malaysia
                    </p>
                    
                    <form method="post" action="backend/login.php" id="loginForm">
                        <div class="form-group">
                            <label for="nokp" class="form-label"><b>No. Kad Pengenalan:</b></label>
                            <input type="text" name="nokp" id="nokp" class="form-control" placeholder="Masukkan No. Kad Pengenalan" value="<?php echo isset($_GET['nokp']) ? $_GET['nokp'] : ''; ?>">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password" class="form-label"><b>Kata Laluan:</b></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Kata Laluan">
                        </div>
                        <div class="text-right checkbox">
                            <input class="form-check-input" type="checkbox" id="showPass"> Tunjuk kata laluan
                        </div>
                        <br>
                        <button id="btnSubmit" name="login" type="submit" class="btn btn-login btn-block mt-3">LOG MASUK</button>
                    </form>

                    <div class="forgot-password">
                        <button class="btn btn-link" id="forgotPasswordBtn" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                            Lupa Katalaluan?
                        </button>
                    </div>

                    <div class="sign-up">
                        <p>Tidak mempunyai akaun <a href="register.php">Daftar Masuk</a> sekarang.</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Lupa Katalaluan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="resetForm" method="POST" action="backend/send_reset_email.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mel:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan e-mel anda">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-custom">HANTAR EMAIL</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--==================JS=================-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
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
        document.getElementById('resetForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;

            fetch('backend/send_reset_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to the form-reset-password.php page
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    document.getElementById('response').innerText = data.message;
                }
            });
        });
    </script>

    <script>
        // Show/Hide Password Toggle
        $('#showPass').click(function() {
            var pass = $('input[name=password]');
            if (pass.attr('type') == 'text') {
                pass.attr('type', 'password');
            } else {
                pass.attr('type', 'text');
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function (event) {
            let nokp = document.getElementById('nokp');
            let password = document.getElementById('password');
            let valid = true;

            // Clear previous highlights
            nokp.style.borderColor = '';
            password.style.borderColor = '';

            // Validate nokp
            if (nokp.value.trim() === '') {
                nokp.style.borderColor = 'red';
                valid = false;
            }

            // Validate password
            if (password.value.trim() === '') {
                password.style.borderColor = 'red';
                valid = false;
            }

            if (!valid) {
                event.preventDefault(); // Prevent form submission
            }
        });

        // Check URL parameters for error status
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const nokpInput = document.getElementById('nokp');
        const passwordInput = document.getElementById('password');

        if (status === 'invalid') {
            Swal.fire({
                icon: 'error',
                title: 'Log Masuk Gagal!',
                text: 'No. Kad Pengenalan atau Kata Laluan Tidak Sah',
            });

            nokpInput.style.borderColor = 'red';
            passwordInput.style.borderColor = 'red';

        } else if (status === 'wrong_password') {
            Swal.fire({
                icon: 'error',
                title: 'Log Masuk Gagal!',
                text: 'Kata Laluan Salah',
            });

            passwordInput.style.borderColor = 'red';
        }

        // Redirect to login page
        document.getElementById('btnReturn').addEventListener('click', function() {
            window.location.href = 'index.php';
        });
    </script>

    <script>
        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

        if (getURL == 'registered') {
            Swal.fire({
                icon: 'success',
                title: 'Daftar Akaun Berjaya!',
                text: 'AKAUN BERJAYA DIDAFTAR',
            }).then(() => {
                // Remove the status=loggedIn parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }
    </script>

</body>
</html>
