<?php 
include 'database/config.php';
include 'session/security.php';

$page = 'Reset Katalaluan | Sistem Pengurusan Data dan Tempahan Buku Ibnu Firnas';

$email = isset($_GET['email']) ? $_GET['email'] : '';
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

    <style>
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
    </style>

</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-sm-8">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="text-center mb-4">Reset Kata Laluan</h4>
                        <form id="resetPasswordForm" method="POST" action="backend/reset_password.php">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            <div class="mb-3">
                                <label for="reset_code" class="form-label">Kod Pengesahan:</label>
                                <input type="text" id="reset_code" name="reset_code" class="form-control" placeholder="Masukkan kod pengesahan" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Kata Laluan Baharu:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Masukkan kata laluan baharu" required>
                            </div>
                            <button type="submit" class="btn btn-custom">Reset Kata Laluan</button>
                        </form>
                        <p id="responseReset" class="mt-3 text-danger text-center"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('backend/reset_password.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to login page
                    window.location.href = 'login-page.php?status=resetSuccess';
                } else {
                    // Show error message
                    document.getElementById('responseReset').innerText = data.message;
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
