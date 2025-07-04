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

    <!--================Leaflet================-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

     <!--==============AOS CSS================-->
     <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/index-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <title><?php echo $page; ?></title>

    <style>

/* Preloader Styles */
    .preload {
        font-family: "Quicksand";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: transparent; /* Background color */
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    /* Circle Animation */
    .circle {
        width: 100px;
        height: 100px;
        border: 5px solid #fff;
        border-top: 5px solid var(--cedar);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Text Styles */
    .text {
        color: var(--cedar);
        font-size: 24px;
        margin-top: 10px;
        }

    /* Circle Spin Animation */
    @keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    }
</style>

</head>
<body>
  
<div class="preload" id="preload">
  <div class="circle"></div>
  <p class="text">Menunggu...</p>
  <p class="text">Sila tunggu sebentar.</p>
</div>

  <script>
      document.addEventListener("DOMContentLoaded", function () {
      // Preloader logic
      window.onload = function () {
          setTimeout(function () {
              document.getElementById('preload').style.opacity = '0';
              document.getElementById('preload').style.pointerEvents = 'none';
              setTimeout(function () {
                  document.getElementById('preload').style.display = 'none';

                  // Redirect after preloader
                  const urlParams = new URLSearchParams(window.location.search);
                  const target = urlParams.get('target');

                  // Pilih halaman destinasi berdasarkan `target` atau laluan lalai
                  if (target) {
                      window.location.href = target;
                  } else if (window.location.href.includes("status=loggedIn")) {
                      window.location.href = 'dashboard.php';
                  } else if (window.location.href.includes("status=registered")) {
                      window.location.href = 'login-page.php';
                  } else {
                      window.location.href = 'default-page.php'; // Default fallback
                  }

              }, 500); // CSS transition time
          }, 2000); // Delay before hiding preloader
      };
  });

  </script>
</body>
</html>
