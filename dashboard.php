<?php 

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database/config.php';
include 'session/security.php';

// check if user logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-page.php?status=unauthorized");
    exit();
}

//set nokp to user_id
$nokp = $_SESSION['user_id'];

// get user info
$stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (isset($_SESSION['user_id'])) {
    $nokp = $_SESSION['user_id'];

    // Get records info
    $stmt = $connect->prepare("SELECT fine, status_payment FROM records WHERE nokp = ? AND fine > 0 AND status_payment = 0");
    $stmt->bind_param("s", $nokp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $outstandingFine = true;
    }
}

$page = 'Dashboard | Ibnu Firnas Knowledge Centre';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/banner-style.css?v=<?php echo time(); ?>">

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
        .welcome-container {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body> 

    <?php include 'includes/sidebar.php' ?>

    <!-- Toast Notification -->
    <div id="fineToast" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050; display: none;">
        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Anda mempunyai denda yang belum diselesaikan. Klik di sini untuk menyelesaikan.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    
    <div class="container mt-5 height-100">
        <br>
        <h1>Dashboard</h1>
        <div class="welcome-container">
            <p>SELAMAT DATANG, <?php echo htmlspecialchars($user['nama_penuh']); ?>!</p>
        </div>
        <br>
        <h5>Maklumat Anda</h5>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td><?php echo htmlspecialchars($user['jawatan']); ?></td>
                </tr>
                <?php if (!empty($user['matriks'])): ?>
                <tr>
                    <th>No. Matriks</th>
                    <td><?php echo htmlspecialchars($user['matriks']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($user['tahun'])): ?>
                <tr>
                    <th>Tahun</th>
                    <td><?php echo htmlspecialchars($user['tahun']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($user['program'])): ?>
                <tr>
                    <th>Program</th>
                    <td><?php echo htmlspecialchars($user['program']); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-md-4">
                <a href="books.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class='bx bxs-book bx-lg'></i>
                            <h5 class="card-title mt-2">Katalog Buku</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="reservations.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bx bxs-book-bookmark bx-lg"></i>
                            <h5 class="card-title mt-2">Tempahan & Pinjaman Buku</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="records.php" class="text-decoration-none">
                    <div class="card text-center">
                        <div class="card-body">
                        <i class='bx bxs-bookmark-alt bx-lg'></i>
                            <h5 class="card-title mt-2">Rekod Pinjaman Buku</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Get the user's name from PHP
        const userName = "<?php echo htmlspecialchars($user['nama_penuh']); ?>";
        
        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

        if (getURL == 'loggedIn') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya Log Masuk!',
                text: 'SELAMAT DATANG, ' + userName + '!'
            }).then(() => {
                // Remove the status=loggedIn parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($outstandingFine): ?>
                // Tunjukkan toast jika ada denda
                const fineToastElement = document.getElementById('fineToast');
                fineToastElement.style.display = 'block';

                const toast = new bootstrap.Toast(fineToastElement.querySelector('.toast'), {
                    autohide: false
                });
                toast.show();

                // Alihkan pengguna ke records.php jika toast diklik
                fineToastElement.querySelector('.toast-body').addEventListener('click', function () {
                    window.location.href = 'records.php';
                });
            <?php endif; ?>
        });
    </script>

</body>

</html>
