<?php 
    include 'database/config.php';
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

    <!-- =======================CSS======================= -->
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
    body {
        background-color: var(--main-color);
    }

    .custom-grey-button {
        background-color: grey;
        border-color: grey;
        color: white;
    }

    .custom-grey-button:hover {
        background-color: #A2A2A2;
        color: white;
    }

    .pif-logo {
        width: 150px;
    }
</style>
<body>
    
    <!-- Background Video -->
    <video autoplay loop muted>
        <source src="../images/bg.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Login Form -->
    <div class="login mt-4">
        <div class="login-form h-100">
            <div class="container h-100">
                <div class="row align-items-center h-100">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="images/pif-logo.png" class="pif-logo" alt="pif">
                                    <h5>Sistem Pengurusan Data & Tempahan Buku <br>Perpustakaan Ibnu Firnas</h5>
                                    <p class="text-muted">Kolej Vokasional Kuala Selangor<br>Kementerian Pendidikan Malaysia</p>
                                </div>
                                <hr>
                                <form id="loginForm" action="backend/login.php" method="post">
                                    <h5>LOG MASUK ADMIN</h5>
                                    <div id="form-login">
                                        <div class="form-group">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" placeholder="Masukkan Nama Admin" required>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Masukkan Kata Laluan" required>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="showPass"> Tunjuk kata laluan
                                                </div>
                                            </div>
                                            <br>
                                            Tidak mempunyai akaun admin? Hubungi <a href="#" style="color: black;">013-755-8636</a>
                                        </div>
                                    </div>
                                    <br>
                                    <button id="btnSubmit" name="login" type="submit" class="btn btn-primary w-100">Log Masuk</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <?php include 'includes/footer.php'; ?>

    <!--=============== Bootstrap 5.2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!--=============== jQuery ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!--=============== Datatables ===============-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // show or hide password
        $('#showPass').click(function() {
            var pass = $('input[name=password]');

            if (pass.attr('type') == 'text') {
                pass.attr('type', 'password');
            } else {
                pass.attr('type', 'text');
            }
        });

        // sweet alert catch
        const url = new URLSearchParams(window.location.search);
        const status = url.get('status');

        if (status == 'invalid') {
            Swal.fire({
                icon: 'error',
                title: 'Log Masuk Gagal !',
                text: 'No. Kad Pengenalan atau Kata Laluan Tidak Sah'
            }).then(() => {
                // Remove the status=invalid parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        } else if (status == 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Log Masuk Gagal !',
                text: 'Sistem Tidak Dapat Diakses!'
            }).then(() => {
                // Remove the status=error parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }
    </script>

</body>
</html>
