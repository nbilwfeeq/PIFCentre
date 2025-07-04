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

// Set nokp to user_id
$nokp = $_SESSION['user_id'];

// Get user info
$stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user exists
if (!$user) {
    header("Location: login-page.php"); 
    exit();
}

$page = 'Kemaskini Katalaluan | Ibnu Firnas Knowledge Centre';
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
    <link rel="stylesheet" href="styles/books-style.css?v=<?php echo time(); ?>">
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
        .main-container {
            display: flex;
            min-height: 100vh;
            margin-top: 15vh;
        }

        .profile-content {
            flex-grow: 1;
            padding-left: 50px;
            background-color: transparent;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); */
        }
        .profile-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        /* .hidden {
            display: none;
        } */

    </style>

</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="container-fluid main-container">
        
        <?php include 'includes/inner-sidebar.php'; ?>

        <!-- Profile Page Content -->
        <div class="profile-content">
            <h1 class="mb-3">Kemaskini Katalaluan</h1>
            <!-- <p class="text-muted">Keep your personal details private. Information you add here is visible to anyone who can view your profile.</p> -->

            <form action="backend/edit-password.php" method="post">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="old-password" class="form-label fw-bold">Katalaluan Lama</label>
                            <input type="password" class="form-control" name="old-password" placeholder="Masukkan katalaluan lama..." required>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="new-password" class="form-label fw-bold">Katalaluan Baharu</label>
                            <input type="password" class="form-control" name="new-password" placeholder="Masukkan katalaluan baharu..." required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label fw-bold">Pengesahan Katalaluan Baharu</label>
                            <input type="password" class="form-control" name="confirm-password" placeholder="Masukkan katalaluan baharu sekali lagi..." required>
                        </div>
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showPass"> Tunjuk kata laluan
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        
                    </div>
                </div>

                <br><br>
                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary" type="reset">Reset</button>
                    <button class="btn btn-primary" type="submit">Kemaskini Katalaluan</button>
                </div>
            </form>
        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!--==================JS=================-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Show or hide passwords
    document.getElementById('showPass').addEventListener('change', function() {
        const passwordFields = [
            document.querySelector('input[name="old-password"]'),
            document.querySelector('input[name="new-password"]'),
            document.querySelector('input[name="confirm-password"]'),
        ];

        passwordFields.forEach(field => {
            if (this.checked) {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        });
    });

    const parameter = new URLSearchParams(window.location.search);
    const getURL = parameter.get('status');

    if (getURL == 'password_updated') {
        Swal.fire({
            icon: 'success',
            title: 'Berjaya!',
            text: 'Katalaluan berjaya dikemasini!',
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
    } else if (getURL == 'password_notMatch') {
        Swal.fire({
            icon: 'error',
            title: 'Tidak Berjaya!',
            text: 'Katalaluan tidak sama, Sila cuba sekali lagi!',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: '#c8504e',
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