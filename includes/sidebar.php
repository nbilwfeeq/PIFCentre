<?php

include 'database/config.php';

// Set nokp to user_id
$nokp = $_SESSION['user_id'];

// Get user info
$userStmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
if (!$userStmt) {
    die("Error preparing statement: " . $connect->error);
}
$userStmt->bind_param("s", $nokp);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

// Get notification info
$notificationsStmt = $connect->prepare("SELECT * FROM notifications WHERE nokp = ? ORDER BY info_date DESC, info_time DESC");
if (!$notificationsStmt) {
    die("Error preparing statement: " . $connect->error);
}
$notificationsStmt->bind_param("s", $nokp);
$notificationsStmt->execute();
$notificationsResult = $notificationsStmt->get_result();
$notifications = $notificationsResult->fetch_all(MYSQLI_ASSOC);

// Get unread notifications count
$unreadStmt = $connect->prepare("SELECT COUNT(*) AS total FROM notifications WHERE nokp = ? AND is_read = 0");
if (!$unreadStmt) {
    die("Error preparing statement: " . $connect->error);
}
$unreadStmt->bind_param("s", $nokp);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadCount = $unreadResult->fetch_assoc()['total'];

// Get jenis buku info
$jenisStmt = $connect->prepare("SELECT * FROM jenis");
if (!$jenisStmt) {
    die("Error preparing statement: " . $connect->error);
}
$jenisStmt->execute();
$jenisResult = $jenisStmt->get_result();
$jenisBuku = $jenisResult->fetch_all(MYSQLI_ASSOC);

// Get kategori buku info
$kategoriStmt = $connect->prepare("SELECT * FROM kategori");
if (!$kategoriStmt) {
    die("Error preparing statement: " . $connect->error);
}
$kategoriStmt->execute();
$kategoriResult = $kategoriStmt->get_result();
$kategoriBuku = $kategoriResult->fetch_all(MYSQLI_ASSOC);

// Get reservation info for the logged-in user
$stmt = $connect->prepare("SELECT * FROM reservations WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$reservationsResult = $stmt->get_result();
$reservations = $reservationsResult->fetch_all(MYSQLI_ASSOC);

// Get loan info for the logged-in user
$stmt = $connect->prepare("SELECT * FROM loans WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$loansResult = $stmt->get_result();
$loans = $loansResult->fetch_all(MYSQLI_ASSOC);

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

// Updated search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "
    SELECT 
        b.id_buku, 
        b.judul_buku, 
        j.jenisBuku, 
        k.kategoriBuku, 
        b.noPerolehan_buku, 
        b.noPanggil_buku, 
        b.lokasi_buku, 
        b.bahasa_buku, 
        b.gambar_buku,
        b.pengarang_buku, 
        b.isbn,           
        b.penerbit_buku,   
        b.tempat_terbit,   
        b.tahun_terbit,   
        b.mukasurat_buku,  
        (SELECT SUM(b2.kuantiti_buku)
         FROM books b2
         WHERE b2.isbn = b.isbn) AS kuantiti_buku
    FROM books b
    LEFT JOIN jenis j ON b.id_jenisBuku = j.id_jenisBuku
    LEFT JOIN kategori k ON b.id_kategoriBuku = k.id_kategoriBuku
    WHERE 1=1
    ";

if ($search) {
    $query .= "
        AND (
            b.judul_buku LIKE ? OR 
            b.pengarang_buku LIKE ? OR 
            b.isbn LIKE ? OR 
            j.jenisBuku LIKE ? OR 
            k.kategoriBuku LIKE ?
        )
    ";
    $searchTerm = "%" . $search . "%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm];
    $types = str_repeat('s', count($params));
}

// Add grouping to ensure only one result per ISBN
$query .= " GROUP BY b.isbn";

$booksStmt = $connect->prepare($query);
if (!$booksStmt) {
    die("Error preparing statement: " . $connect->error);
}

if (!empty($types)) {
    $booksStmt->bind_param($types, ...$params);
}
$booksStmt->execute();
$booksResult = $booksStmt->get_result();
$books = $booksResult->fetch_all(MYSQLI_ASSOC);


?>

<!--==============AOS CSS================-->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../styles/main-style.css">

<style>
    
<?php include 'styles/sidebar-style.php'; ?>

</style>

<?php include 'loader.php'; ?>

<body id="body-pd">
    <!-- Background Video -->
    <!-- <video autoplay loop muted>
        <source src="images/bg.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video> -->

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

    <header class="header" id="header">
        <div class="header_toggle"><i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_content">
            
            <div class="d-flex align-items-center search-container">
                <form method="GET" action="books.php" class="search-form">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control search-bar"  
                            name="search" 
                            placeholder="Cari Buku, Jenis, atau Kategori..." 
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                        <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt-2'></i></button>
                        <!-- <button type="button" class="btn btn-secondary" id="reset-btn"><i class='bx bx-refresh'></i></button> -->
                    </div>
                </form>
            </div>

            <div class="profile">
                <a href="profile.php" title="Profil Anda">
                    <img src="images/profile-pic/<?php echo htmlspecialchars($user['gambarUser']); ?>" alt="Profile Picture">
                </a>
                <button class="dropdown-btn">
                    <i class="bx bx-chevron-down"></i>
                </button>
                <div class="dropdown-content">
                    <p><strong>Profil Anda</strong></p>
                    <a href="profile.php">
                    <div class="current-account">
                        <img src="images/profile-pic/<?php echo htmlspecialchars($user['gambarUser']); ?>" alt="Profile Picture">
                        <div>
                            <p><?php echo htmlspecialchars($user['nama_penuh']); ?></p>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    </div>
                    </a>
                    <hr>
                    <p></p>
                    <a href="update-profile.php">Kemaskini profil anda</a>
                    <a href="backend/logout.php" id="logout-button-2" title="Log Keluar"><b style="color: red;">Log Keluar</b></a>
                </div>
            </div>
        </div>
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <div class="nav_list">
                <a href="dashboard.php" class="nav_link" id="nav-dashboard" title="Dashboard">
                    <img src="images/pif-icon.png" class="nav_img" alt="Logo">
                    <span class="tooltip">Dashboard</span>
                </a>
                <a href="books.php" class="nav_link" id="nav-books" title="Katalog">
                    <i class='bx bx-book nav_icon'></i>
                    <span class="tooltip">Katalog</span>
                </a>
                <a href="reservations.php" class="nav_link" id="nav-reservations" title="Tempahan & Pinjaman">
                    <i class='bx bx-book-bookmark nav_icon'></i>
                    <span class="tooltip">Tempahan & Pinjaman</span>
                </a>
                <a href="records.php" class="nav_link" id="nav-records" title="Rekod Pinjaman">
                    <i class='bx bx-bookmark-alt nav_icon'></i>
                    <span class="tooltip">Rekod</span>
                </a>
                <button class="nav_link-notification notification-btn" title="Notifikasi">
                    <i class='bx bx-bell nav_icon'></i>
                    <span class="tooltip">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                        <span class="notification-count"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </button>
                <div class="notification-dropdown">
                    <div class="notification-header">
                        <strong>Notifikasi</strong>
                        <button class="clear-notifications" onclick="clearAllNotifications()">Kosongkan Semua</button>
                    </div>
                    <ul id="notifications" class="notification-list">
                        <?php if (empty($notifications)): ?>
                            <li>Tidak ada notifikasi baru.</li>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <a href="reservations.php">
                                    <li id="notification-<?php echo $notification['id_notification']; ?>">
                                        <?php echo htmlspecialchars($notification['info']); ?> 
                                        <br><br>
                                        <small>(<?php echo $notification['info_date']; ?> <?php echo $notification['info_time']; ?>)</small>
                                        <!-- <button class="delete-btn" onclick="deleteNotification(<?php echo $notification['id_notification']; ?>)">&#x2716;</button> -->
                                    </li>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                </div>
            </div> 
            <a href="backend/logout.php" class="nav_link" id="logout-button" title="Log Keluar"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">Log Keluar</span> </a>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.all.min.js"></script>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1500, // Animation duration
            once: true      // Whether animation should happen only once
        });

        document.addEventListener("DOMContentLoaded", function() {
            const resetButton = document.getElementById("reset-btn");
            const searchInput = document.querySelector('input[name="search"]');

            if (resetButton) {
                resetButton.addEventListener("click", function() {
                    searchInput.value = ""; // Clear the search bar
                    window.location.href = "books.php"; // Reload the page to reset the search
                });
            }
        });
    </script>

    <script>
        function updateNotificationCount() {
            fetch('backend/get_unread_count.php', {
                method: 'POST',
                body: new URLSearchParams({ nokp: '<?php echo $nokp; ?>' })
            })
            .then(response => response.json())
            .then(data => {
                const notificationCount = document.querySelector('.notification-count');
                if (data.unreadCount > 0) {
                    // If the notification count does not exist, create it
                    if (!notificationCount) {
                        const newCount = document.createElement('span');
                        newCount.classList.add('notification-count');
                        newCount.textContent = data.unreadCount;
                        document.querySelector('.notification-btn').appendChild(newCount);
                    } else {
                        // Update the notification count
                        notificationCount.textContent = data.unreadCount;
                    }
                } else if (notificationCount) {
                    // Remove notification count if no unread notifications
                    notificationCount.remove();
                }
            })
            .catch(error => console.error('Error updating notification count:', error));
        }


        function clearAllNotifications() {
            Swal.fire({
                title: 'Adakah Anda Pasti?',
                text: 'Semua notifikasi anda akan dipadamkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Padam!',
                cancelButtonText: 'Tidak, Simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an AJAX request to delete notifications
                    fetch('backend/clear_notifications.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            nokp: '<?php echo $nokp; ?>'
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data == 'success') {
                            Swal.fire('Berjaya Dipadamkan!', 'Semua notifikasi anda telah dipadamkan.', 'success');
                            document.getElementById('notifications').innerHTML = '<li>Tidak ada notifikasi baru.</li>';
                        } else {
                            Swal.fire('Error!', 'There was an error clearing notifications.', 'error');
                        }
                    });
                }
            });
        }

        function deleteNotification(id_notification) {
            Swal.fire({
                title: 'Adakah Anda Pasti?',
                text: 'Notifikasi ini akan dipadamkan secara kekal!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Padam!',
                cancelButtonText: 'Tidak, Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('backend/delete_notification.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id_notification=${id_notification}&nokp=<?php echo $nokp; ?>`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            // Tampilkan notifikasi keberhasilan
                            Swal.fire({
                                icon: 'success',
                                title: 'Berjaya!',
                                text: 'Notifikasi telah berjaya dipadam!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                background: '#28a745',
                                iconColor: '#fff',
                                color: '#fff',
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 0500); 
                        } else {
                            Swal.fire('Error!', 'Gagal memadam notifikasi. Sila cuba lagi.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Gagal memadam notifikasi.', 'error');
                    });
                }
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropdownBtn = document.querySelector(".dropdown-btn");
            const dropdownContent = document.querySelector(".dropdown-content");

            dropdownBtn.addEventListener("click", () => {
                dropdownContent.classList.toggle("show");
                dropdownBtn.classList.toggle("active");
            });

            // Close dropdown if clicked outside
            document.addEventListener("click", (event) => {
                if (!dropdownBtn.contains(event.target) && !dropdownContent.contains(event.target)) {
                    dropdownContent.classList.remove("show");
                    dropdownBtn.classList.remove("active");
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const notificationBtn = document.querySelector(".notification-btn");
            const notificationDropdown = document.querySelector(".notification-dropdown");

            // Toggle notification dropdown
            notificationBtn.addEventListener("click", () => {
                notificationDropdown.classList.toggle("show");

                // Remove notification count when the list is opened
                const notificationCount = document.querySelector('.notification-count');
                if (notificationCount) {
                    notificationCount.remove();
                }

                // Update unread notifications to marked as seen in the backend
                updateNotificationAsSeen();
            });

            // Close dropdown when clicking outside
            document.addEventListener("click", (event) => {
                if (!notificationDropdown.contains(event.target) && !notificationBtn.contains(event.target)) {
                    notificationDropdown.classList.remove("show");
                }
            });
        });

        // Function to mark notifications as seen
        function updateNotificationAsSeen() {
            fetch('backend/mark_notifications_as_seen.php', {
                method: 'POST',
                body: new URLSearchParams({
                    nokp: '<?php echo $nokp; ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Notifications marked as seen.");
                } else {
                    console.error("Error marking notifications as seen.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId);

            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    nav.classList.toggle('show');
                    toggle.classList.toggle('bx-x');
                    bodypd.classList.toggle('body-pd');
                    headerpd.classList.toggle('body-pd');
                    headerpd.classList.toggle('header-expanded'); // Add class for expanded header
                });
            }
        };

        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

        /*===== LINK ACTIVE =====*/
        const linkColor = document.querySelectorAll('.nav_link');

        function colorLink() {
            if (linkColor) {
                linkColor.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        }
        linkColor.forEach(l => l.addEventListener('click', colorLink));

        // Highlight active link based on current URL
        const currentUrl = window.location.pathname.split('/').pop();
        const navLinks = {
            'dashboard.php': document.getElementById('nav-dashboard'),
            'books.php': document.getElementById('nav-books'),
            'reservations.php': document.getElementById('nav-reservations'),
            'records.php': document.getElementById('nav-records'),
            'update-profile.php': document.getElementById('nav-profile')
        };

        if (navLinks[currentUrl]) {
            navLinks[currentUrl].classList.add('active');
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

        // SweetAlert for logout confirmation
        const logoutButton2 = document.getElementById('logout-button-2');
        if (logoutButton2) {
            logoutButton2.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Adakah Anda Pasti?',
                    text: "Anda akan di log keluar!",
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
    });
    </script>

    <script>
        document.querySelectorAll('input[name="search"], select[name="jenis"], select[name="kategori"]').forEach(input => {
            input.addEventListener('input', function() {
                let search = document.querySelector('input[name="search"]').value;
                let jenis = document.querySelector('select[name="jenis"]').value;
                let kategori = document.querySelector('select[name="kategori"]').value;
                window.location.href = `books.php?search=${encodeURIComponent(search)}&jenis=${encodeURIComponent(jenis)}&kategori=${encodeURIComponent(kategori)}`;
            });
        });
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
