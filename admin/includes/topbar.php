<?php

include 'database/config.php';

// Get unread notification count
$unreadQuery = "SELECT COUNT(*) AS total FROM notifications_admin WHERE is_read = 0";
$unreadResult = $connect->query($unreadQuery);
if (!$unreadResult) {
    die("Error fetching notifications: " . $connect->error);
}
$unreadCount = $unreadResult->fetch_assoc()['total'];
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../styles/main-style.css">

<style>
    /* Mobile (Smartphone) */
    @media (max-width: 480px) {
        .logo-pif {
            width: 2rem;
        }
    }

    /* Desktops */
    @media (min-width: 1280px) {
        .logo-pif {
            width: 4.5rem;
        }
    }

    /* Huge size (Larger screen) */
    @media (min-width: 1281px) {
        .logo-pif {
            width: 4.5rem;
        }
    }

    .danger {
        color: var(--red) !important;
    }

    /* Smooth hover effect */
    .navbar-nav .nav-link, .navbar-nav .nav-link i {
        transition: color 0.1s ease;
    }

    .navbar-nav .nav-link:hover, .navbar-nav .nav-link i:hover {
        color: var(--cedar) !important;
    }

    .navbar-nav .nav-link i {
        margin-right: 5px;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
    }

    .navbar-brand img {
        margin-right: 10px;
    }

    /* Active link style */
    .navbar-nav .nav-link.active {
        color: var(--cedar);
    }

    /* Notification count badge */
    .notification-count {
        background-color: var(--red);
        color: white;
        border-radius: 50%;
        padding: 1px 6px;
        font-size: 0.8rem;
        margin-left: 5px;
    }
</style>

<?php include 'includes/loader.php'; ?>

<!-- Background Video -->
<video autoplay loop muted>
    <source src="../images/bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <img src="../images/pif-logo.png" class="logo-pif" alt="Logo">
            <b>PIF</b> Centre
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="dashboard.php"><i class='bx bxs-dashboard'></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php"><i class='bx bxs-user'></i>Data Pengguna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="books.php"><i class='bx bxs-data'></i>Data Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservations.php" id="reservations-link">
                        <i class='bx bxs-message-square-detail'></i>
                        Senarai Tempahan
                        <?php if ($unreadCount > 0): ?>
                        <span class="notification-count"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loans.php"><i class='bx bxs-file'></i>Senarai Pinjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="records.php"><i class='bx bxs-bookmark-alt' ></i></i>Rekod Pinjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php"><i class='bx bx-cog'></i>Tetapan</a>
                </li>
                <li class="nav-item">
                    <a href="backend/logout.php" id="logout-button" class="nav-link danger"><i class='bx bx-log-out'></i>Log Keluar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.all.min.js"></script>

<script>
    // SweetAlert for logout confirmation
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Adakah Anda Pasti?',
                text: "Anda Akan Dilog Keluar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Saya Pasti!',
                cancelButtonText: 'Tidak, Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'backend/logout.php';
                }
            });
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        // Add active class to the current page link
        navLinks.forEach(link => {
            if (link.href.includes(currentPath)) {
                link.classList.add('active');
            }
        });

        // Add click event to mark notifications as read
        const reservationsLink = document.getElementById('reservations-link');
        const notificationCount = document.querySelector('.notification-count');

        reservationsLink.addEventListener('click', function() {
            // Send request to mark notifications as read
            fetch('backend/mark_notifications_as_seen.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Notifications marked as read.");
                    
                    // Hide notification count dynamically
                    if (notificationCount) {
                        notificationCount.style.display = 'none';
                    }
                } else {
                    console.error("Failed to mark notifications as read:", data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

