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

$page = 'Profil Anda | Ibnu Firnas Knowledge Centre';
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .profile-container {
            background-color: transparent;
            border-radius: 10px;
            width: 450px;
            margin-top: 120px;
            margin-left: 380px;
            padding: 30px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
        }
        .profile-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 100px;
        }
        .username {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
        }
        .description {
            text-align: center;
            color: #777;
            font-size: 24px;
            margin-top: 10px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .header .profile img {
            border: 2px solid black;
        }
        .btn-primary {
            border-radius: 120px;
        }
        .btn-secondary {
            background-color: var(--red) !important; 
            border-radius: 120px;
        }

        .btn-secondary:hover {
            background-color: transparent !important; 
            border: 1px solid var(--red) !important;
            color: var(--red) !important;
        }
    </style>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="container mt-5 ">
        <div class="profile-container">
            <!-- Profile Image -->
            <img src="images/profile-pic/<?php echo htmlspecialchars($user['gambarUser']); ?>" alt="Profile Picture" class="profile-img">
            
            <!-- Username -->
            <div class="username"><?php echo htmlspecialchars($user['nama_penuh']); ?></div>
            
            <!-- Description -->
            <div class="description"><?php echo htmlspecialchars($user['email']); ?></div>
            
            <!-- Button Container -->
            <div class="button-container">
                <button class="btn btn-primary" onclick="window.location.href='update-profile.php'">Kemaskini Profil</button>
                <!-- <button class="btn btn-secondary" onclick="window.location.href='update-profile.php'">Log keluar</button> -->
            </div>
        </div>
    </div>

    <br><br>

    <!--==================JS=================-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profilePic');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // SweetAlert for logout confirmation
        const logoutButton = document.getElementById('logout-button');
        if (logoutButton) {
            logoutButton.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Adakah Anda Pasti?',
                    text: "Anda akan dilog keluar!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Saya Pasti!',
                    cancelButtonText: 'Tidak, Batalkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'loading-page.php?target=backend/logout.php';
                    }
                })
            });
        }
    </script>
</body>
</html>
