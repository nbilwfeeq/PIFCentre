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

$page = 'Kemaskini Profil | Ibnu Firnas Knowledge Centre';
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
            <h1 class="mb-3">Kemaskini Profil</h1>
            <!-- <p class="text-muted">Keep your personal details private. Information you add here is visible to anyone who can view your profile.</p> -->

            <!-- Profile Photo -->
            <div class="mb-4">
                <label class="form-label fw-bold">Gambar Anda</label>
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-photo">
                        <img src="images/profile-pic/<?php echo htmlspecialchars($user['gambarUser']); ?>" alt="Profile Picture" id="profilePic" class="rounded-circle" style="width: 80px; height: 80px;">
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">Kemaskini</button>
                </div>
            </div>

            <!-- Modal Structure -->
            <div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-labelledby="updatePhotoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="updatePhotoModalLabel">Kemaskini Gambar Profil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center">
                            <form action="backend/edit-profile-pic.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="profileImage" id="profileImageInput" class="form-control mb-3" accept="image/*" onchange="previewNewImage(event)" required>
                                <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; display: none;" class="mb-3">
                                <button type="submit" class="btn btn-primary">Kemaskini</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <form action="backend/edit-profile-info.php" method="post" enctype="multipart/form-data">
                <!-- Action Type -->
                <input type="hidden" name="action" value="update_info">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_penuh" class="form-label fw-bold">Nama Penuh</label>
                            <input type="text" class="form-control" id="nama_penuh" name="nama_penuh" placeholder="Masukkan nama penuh..." value="<?php echo htmlspecialchars($user['nama_penuh']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nokp" class="form-label fw-bold">No. Kad Pengenalan (<b>Tidak Boleh Diubah</b>)</label>
                            <input type="text" class="form-control" id="nokp" name="nokp" placeholder="Masukkan no kad pengenalan..." value="<?php echo htmlspecialchars($user['nokp']); ?>" readonly>
                        </div>
                        <div class="mt-3">
                            <label for="jawatan" class="form-label fw-bold">Kategori</label>
                            <select name="jawatan" class="form-control uppercase-text" id="jawatan" required>
                                <option value="PELAJAR" <?php echo $user['jawatan'] === 'PELAJAR' ? 'selected' : ''; ?>>PELAJAR</option>
                                <option value="PENSYARAH" <?php echo $user['jawatan'] === 'PENSYARAH' ? 'selected' : ''; ?>>PENSYARAH</option>
                                <option value="PEKERJA" <?php echo $user['jawatan'] === 'PEKERJA' ? 'selected' : ''; ?>>PEKERJA</option>
                                <option value="LAIN-LAIN" <?php echo $user['jawatan'] === 'LAIN-LAIN' ? 'selected' : ''; ?>>LAIN-LAIN</option>
                            </select>
                        </div>
                        <div class="pelajar-info hidden">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" class="form-control uppercase-text" id="tahun">
                                <option value="1 SVM" <?php echo $user['tahun'] === '1 SVM' ? 'selected' : ''; ?>>1 SVM</option>
                                <option value="2 SVM" <?php echo $user['tahun'] === '2 SVM' ? 'selected' : ''; ?>>2 SVM</option>
                                <option value="1 DVM" <?php echo $user['tahun'] === '1 DVM' ? 'selected' : ''; ?>>1 DVM</option>
                                <option value="2 DVM" <?php echo $user['tahun'] === '2 DVM' ? 'selected' : ''; ?>>2 DVM</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan email..." value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="notel" class="form-label fw-bold">No. Telefon</label>
                            <input type="text" class="form-control" id="notel" name="notel" 
                                placeholder="Masukkan no telefon..." 
                                value="<?php echo isset($user['notel']) && !empty($user['notel']) ? htmlspecialchars($user['notel']) : ''; ?>" 
                                required>
                        </div>
                        <div class="pelajar-info hidden">
                            <label for="matriks" class="form-label fw-bold">No. Matriks</label>
                            <input type="text" name="matriks" class="form-control uppercase-text" value="<?php echo htmlspecialchars($user['matriks']); ?>" placeholder="Masukkan No. Matriks Pelajar">
                        </div>
                        <div class="pelajar-info hidden">
                            <label for="program">Program</label>
                            <select name="program" class="form-control uppercase-text" id="program">
                                <option value="TEKNOLOGI KOMPUTERAN" <?php echo $user['program'] === 'TEKNOLOGI KOMPUTERAN' ? 'selected' : ''; ?>>Teknologi Komputeran</option>
                                <option value="ANIMASI 3D" <?php echo $user['program'] === 'ANIMASI 3D' ? 'selected' : ''; ?>>Animasi 3D</option>
                                <option value="PEMASARAN" <?php echo $user['program'] === 'PEMASARAN' ? 'selected' : ''; ?>>Pemasaran</option>
                                <option value="PERAKAUNAN" <?php echo $user['program'] === 'PERAKAUNAN' ? 'selected' : ''; ?>>Perakaunan</option>
                                <option value="SENI KULINARI" <?php echo $user['program'] === 'SENI KULINARI' ? 'selected' : ''; ?>>Seni Kulineri</option>
                                <option value="LAIN-LAIN" <?php echo $user['program'] === 'LAIN-LAIN' ? 'selected' : ''; ?>>Lain-lain</option>
                            </select>
                        </div>
                    </div>
                </div>

                <br><br>
                <!-- Action Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary" type="reset">Reset</button>
                    <button class="btn btn-primary" type="submit" name="update">Kemaskini Profil</button>
                </div>
            </form>

        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!--==================JS=================-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    function previewNewImage(event) {
        const reader = new FileReader();
        const imagePreview = document.getElementById('imagePreview');
        
        reader.onload = function () {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('updateProfileImageForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('backend/edit-profile-pic.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data) => {
                if (data.status === 'success') {
                    document.getElementById('profilePic').src = `images/profile-pic/${data.image}`;
                    alert(data.message);

                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('updatePhotoModal'));
                    modal.hide();
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
    });


    // Toggle visibility of fields based on jawatan selection
    document.addEventListener("DOMContentLoaded", function() {
        togglePelajarInfo(); // Initial toggle on page load

        document.getElementById('jawatan').addEventListener('change', function() {
            togglePelajarInfo(); // Toggle when dropdown changes
        });

        function togglePelajarInfo() {
            var jawatan = document.getElementById('jawatan').value;
            var pelajarInfo = document.querySelectorAll('#pelajar-info'); // Select all pelajar-info sections
            
            if (jawatan === 'PELAJAR') {
                pelajarInfo.forEach(function(info) {
                    info.classList.remove('hidden'); // Show pelajar-info sections
                });
            } else {
                pelajarInfo.forEach(function(info) {
                    info.classList.add('hidden'); // Hide pelajar-info sections
                });
            }
        }
    });
</script>

</body>
</html>