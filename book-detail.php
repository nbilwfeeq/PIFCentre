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

// Set nokp to user_id from session
$nokp = $_SESSION['user_id'];

// Fetch all user info using nokp
$userStmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
if (!$userStmt) {
    die("Error preparing statement: " . $connect->error);
}
$userStmt->bind_param("s", $nokp);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    echo "User not found.";
    header("Location: login-page.php?status=unauthorized");
    exit;
}

// Optionally, you can store more user details if needed from $user array
$nama_penuh = $user['nama_penuh'];

// Get book id 
$bookId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($bookId > 0) {
    // Fetch book details with jenis and kategori names
    $stmt = $connect->prepare("
        SELECT b1.id_buku, b1.judul_buku, b1.pengarang_buku, b1.penerbit_buku, b1.isbn, b1.bahasa_buku, b1.gambar_buku, b1.lokasi_buku,
            j.id_jenisBuku, j.jenisBuku, k.id_kategoriBuku, k.kategoriBuku
        FROM books b1
        JOIN jenis j ON b1.id_jenisBuku = j.id_jenisBuku
        JOIN kategori k ON b1.id_kategoriBuku = k.id_kategoriBuku
        WHERE b1.id_buku = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $bookDetails = $stmt->get_result()->fetch_assoc();

    if (!$bookDetails) {
        echo "Book not found.";
        header("Location: books.php?status=notfound");
        exit;
    }

    // Fetch and aggregate book quantity
    $stmt = $connect->prepare("
        SELECT SUM(b1.kuantiti_buku) AS total_kuantiti
        FROM books b1
        WHERE b1.isbn = ?
    ");
    $stmt->bind_param("s", $bookDetails['isbn']);
    $stmt->execute();
    $quantityResult = $stmt->get_result()->fetch_assoc();

    $totalQuantity = $quantityResult['total_kuantiti'] ?? 0;

    // Check if book quantity and status
    $isAvailable = $totalQuantity > 0;
    $statusText = $isAvailable ? 'Tersedia' : 'Tidak Tersedia';
    $quantityLeft = $isAvailable ? $totalQuantity : 0;
} else {
    echo "Invalid book ID.";
    header('Location: books.php');
    exit;
}

// Map shorthand codes to full language names
$languageMap = [
    'BI' => 'BAHASA INGGERIS',
    'BM' => 'BAHASA MELAYU',
    'BC' => 'BAHASA CINA'
];

// Fetch the book's language code
$languageCode = htmlspecialchars($bookDetails['bahasa_buku']);

// Display the full language name
$languageDisplay = isset($languageMap[$languageCode]) ? $languageMap[$languageCode] : '-';

// Limit judul_buku to 50 characters, append "..." if longer
$limitedTitle = mb_strimwidth($bookDetails['judul_buku'], 0, 50, '...');

$page = htmlspecialchars($bookDetails['judul_buku']) . ' | Ibnu Firnas Knowledge Centre';
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

    <!--============Bootstrap 5.3.0=============-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <title><?php echo $page; ?></title>

    <style>
        .book-img img {
            width: 400px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
        }
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.75rem auto;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040; /* Ensure this is higher than the sidebar z-index */
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .btn-custom {
            background-color: var(--cedar);
            color: white;
            border: none;
            transition: all 0.1s ease; 
            margin-top: 10px;
        }
        .btn-custom:hover {
            border: 1px solid var(--cedar);
            color: var(--cedar);
            background-color: transparent; 
        }
        .disabled-button {
            background-color: #d3d3d3; 
            color: #888888; 
            cursor: not-allowed; 
            border-color: #d3d3d3;
            pointer-events: none; 
        }

        .btn-return {
            border-radius: 50px;
            color: black;
            border: 0px;
            transition: all 0.1s ease; 
            font-size: 30px;
        }

        .btn-return:hover {
            padding: 10px;
            /* color: var(--red); */
            background-color: rgba(0, 0, 0, 0.1); 
        }

        .box {
            background-color: white;
            border-radius: 10px;
            padding: 30px 60px 30px 60px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
            height: 85vh;
        }
        
        .calendar {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .calendar-header button {
            background-color: grey;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        .calendar-header button:hover {
            background-color: transparent;
            border: 1px solid grey;
            color: grey;
        }
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-top: 10px;
        }
        .day {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }
        .day:hover {
            background-color: #f0f0f0;
        }
        .day.empty {
            background-color: #f9f9f9;
            cursor: default;
        }
        .disabled-day {
            color: #d3d3d3;
            pointer-events: none; /* Prevent click interactions */
            background-color: #f9f9f9;
        }
        .disabled-day:hover {
            background-color: #f9f9f9; /* Keep the same style on hover */
        }
    </style>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <!--Container Main start-->
    <div class="container mt-5 height-100"> 
        <br>
        <div class="row" data-aos="fade-right">
            <div class="col-md-1">
                <a href="books.php" class="btn-return"><i class='bx bx-left-arrow-alt'></i></a>
            </div>
            <div class="col-md-4 book-img">
                <img src="images/books/<?php echo htmlspecialchars($bookDetails['gambar_buku']); ?>" class="img-fluid book-image" alt="<?php echo htmlspecialchars($bookDetails['judul_buku']); ?>">
            </div>
            <div class="col-md-5 box">
                <h2><?php echo htmlspecialchars($limitedTitle); ?></h2>
                <p><?php echo htmlspecialchars($bookDetails['pengarang_buku']); ?></p>
                <br>
                <h5>Maklumat Buku</h5>
                <hr>
                <h5>Penerbit : <?php echo htmlspecialchars($bookDetails['penerbit_buku']); ?></h5>
                <h5>Kategori : <?php echo htmlspecialchars($bookDetails['jenisBuku']); ?> | <?php echo htmlspecialchars($bookDetails['kategoriBuku']); ?></h5>
                <h5>Lokasi : <?php echo htmlspecialchars($bookDetails['lokasi_buku']); ?></h5>
                <h5>Bahasa : <?php echo htmlspecialchars($languageDisplay); ?></h5>
                <h5>ISBN : <?php echo htmlspecialchars($bookDetails['isbn']); ?></h5>
                <br>
                <h5>Kuantiti : <?php echo htmlspecialchars($quantityLeft); ?></h5>
                <h5>Status : <?php echo htmlspecialchars($statusText); ?></h5>
                <br>
                <?php if (!$isAvailable): ?>
                    <p class="text-danger">Harap Maaf, buku ini tidak tersedia untuk tempahan*</p>
                <?php endif; ?>
                <button class="btn-custom <?php echo !$isAvailable ? 'disabled-button' : ''; ?>" 
                <?php echo !$isAvailable ? 'disabled' : ''; ?> data-bs-toggle="modal" data-bs-target="#reserveModal" id="reserveBookBtn">Tempah Sekarang</button>
            </div>
        </div>
    </div>
    <!--Container Main end-->

    <!-- Reserve Form Modal -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">Tempah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="backend/reserve-book.php" method="POST">
                        <input type="hidden" name="id_buku" value="<?php echo htmlspecialchars($bookDetails['id_buku']); ?>">
                        <input type="hidden" name="nama_penuh" value="<?php echo htmlspecialchars($nama_penuh); ?>">
                        <input type="hidden" name="nokp" value="<?php echo htmlspecialchars($nokp); ?>">
                        <div class="mb-3">
                            <label for="judul_buku" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="judul_buku" name="judul_buku" value="<?php echo htmlspecialchars($bookDetails['judul_buku']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <!-- <label for="kategoriBuku" class="form-label">Kategori Buku</label> -->
                            <input type="hidden" class="form-control" id="kategoriBuku" name="kategoriBuku" value="<?php echo htmlspecialchars($bookDetails['kategoriBuku']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <!-- <label for="jenisBuku" class="form-label">Jenis Buku</label> -->
                            <input type="hidden" class="form-control" id="jenisBuku" name="jenisBuku" value="<?php echo htmlspecialchars($bookDetails['jenisBuku']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">No. ISBN Buku</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($bookDetails['isbn']); ?>" readonly>
                        </div>
                        <br>
                        <div id="calendar-container" class="mb-3">
                            <label for="custom_calendar" class="form-label">Pilih Tarikh Tempahan</label>
                            <div class="calendar">
                                <div class="calendar-header">
                                <button id="prevMonth" type="button">Sebelum</button>
                                <h2 id="currentMonth"></h2>
                                <button id="nextMonth" type="button">Seterusnya</button>
                                </div>
                                <div class="calendar-days" id="calendarDays"></div>
                                <input type="hidden" id="reserve_date" name="reserve_date">
                                <input type="hidden" id="return_date" name="return_date">
                            </div>
                        </div>
                        <input type="hidden" class="form-control"  name="gambar_buku" value="<?php echo htmlspecialchars($bookDetails['gambar_buku']); ?>" readonly>
                        <button type="submit" class="btn btn-custom">Tempah Buku</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--==================JS=================-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--==========Custom JS=========-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var reserveModal = new bootstrap.Modal(document.getElementById('reserveModal'));

            document.getElementById('reserveBookBtn').addEventListener('click', function () {
                reserveModal.show();
            });

            document.getElementById('reserve_date').addEventListener('change', function () {
                var reserveDate = new Date(this.value);
                var returnDate = new Date(reserveDate);

                // Ensure return date is at least 7 days after the reservation date
                returnDate.setDate(reserveDate.getDate() + 7);

                // Format return date as YYYY-MM-DD
                var day = ("0" + returnDate.getDate()).slice(-2);
                var month = ("0" + (returnDate.getMonth() + 1)).slice(-2);
                var formattedReturnDate = returnDate.getFullYear() + "-" + month + "-" + day;

                document.getElementById('return_date').value = formattedReturnDate;
            });
        });
    
    </script>

    <script>
        let blockedDates = [];

        // Fetch blocked dates from the server
        fetch('backend/get-blocked-dates.php')
            .then(response => response.json())
            .then(data => {
                blockedDates = data.map(date => new Date(date));
                renderCalendar(); // Re-render the calendar after fetching the dates
            })
            .catch(error => console.error('Error fetching blocked dates:', error));

        // Variables to manage the current date
        const calendarDays = document.getElementById('calendarDays');
        const currentMonth = document.getElementById('currentMonth');
        const prevMonth = document.getElementById('prevMonth');
        const nextMonth = document.getElementById('nextMonth');

        const today = new Date();
        let selectedDate = new Date(today.getFullYear(), today.getMonth(), 1);

        // Variables to store the selected reserve and return dates
        let reserveDate = null;
        let returnDate = null;

        function renderCalendar() {
            const month = selectedDate.getMonth();
            const year = selectedDate.getFullYear();

            currentMonth.textContent = `${selectedDate.toLocaleString('default', { month: 'long' })} ${year}`;
            calendarDays.innerHTML = '';

            const firstDayIndex = new Date(year, month, 1).getDay();
            const lastDay = new Date(year, month + 1, 0).getDate();

            // Add empty spaces before the first day of the month
            for (let i = 0; i < firstDayIndex; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.classList.add('day', 'empty');
                calendarDays.appendChild(emptyDiv);
            }

            // Add days of the month
            for (let i = 1; i <= lastDay; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('day');
                dayDiv.textContent = i;

                const date = new Date(year, month, i);
                const dayOfWeek = date.getDay(); // Get the day of the week (0 = Sunday, 6 = Saturday)

                // Disable weekends (Saturday and Sunday)
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    dayDiv.classList.add('disabled-day');
                } else {
                    // Disable blocked dates
                    const isBlocked = blockedDates.some(blockedDate =>
                        blockedDate.getFullYear() === date.getFullYear() &&
                        blockedDate.getMonth() === date.getMonth() &&
                        blockedDate.getDate() === date.getDate()
                    );

                    if (isBlocked) {
                        dayDiv.classList.add('disabled-day');
                    } else if (date < today.setHours(0, 0, 0, 0)) {
                        // Disable past dates but keep today enabled
                        dayDiv.classList.add('disabled-day');
                    } else {
                        dayDiv.addEventListener('click', () => {
                            selectDate(date);
                        });
                    }
                }

                // Highlight today's date
                if (
                    i === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                ) {
                    dayDiv.style.backgroundColor = 'var(--cedar)';
                    dayDiv.style.color = 'white';
                }

                // Highlight selected reserve and return dates
                if (reserveDate && date.getTime() === reserveDate.getTime()) {
                    dayDiv.style.backgroundColor = 'var(--tortilla)';
                    dayDiv.style.color = 'white';
                }
                if (returnDate && date.getTime() === returnDate.getTime()) {
                    dayDiv.style.backgroundColor = 'grey';
                    dayDiv.style.color = 'white';
                }

                calendarDays.appendChild(dayDiv);
            }
        }


        // Function to select a reserve date and calculate the return date
        function selectDate(date) {
            // Set reserve date
            reserveDate = date;

            // Calculate return date (7 days later)
            returnDate = new Date(date);
            returnDate.setDate(date.getDate() + 7);

            // Update hidden input fields
            document.getElementById('reserve_date').value = formatDate(reserveDate);
            document.getElementById('return_date').value = formatDate(returnDate);

            // Re-render the calendar to highlight selected dates
            renderCalendar();
        }

        // Helper function to format a date as YYYY-MM-DD
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${year}-${month}-${day}`;
        }

        // Event listeners for navigation buttons
        prevMonth.addEventListener('click', () => {
            selectedDate.setMonth(selectedDate.getMonth() - 1);
            renderCalendar();
        });

        nextMonth.addEventListener('click', () => {
            selectedDate.setMonth(selectedDate.getMonth() + 1);
            renderCalendar();
        });

        // Initial render
        renderCalendar();
    </script>

    <script>

        // SweetAlert2 handling
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'reserved') {
            Swal.fire({
                icon: 'success',
                title: 'Tempahan Berjaya',
                text: '<?php echo htmlspecialchars($bookDetails['judul_buku']); ?> BERJAYA DITEMPAH!'
            }).then(() => {
                // Remove the status=invalid parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        } else if (status === 'chooseDate') {
            Swal.fire({
                icon: 'warning',
                title: 'Tempahan Tidak Berjaya',
                text: '<?php echo htmlspecialchars($bookDetails['judul_buku']); ?> TIDAK BERJAYA, SILA PILIH TARIKH DAHULU!'
            }).then(() => {
                // Remove the status=invalid parameter from the URL
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var reserveBookBtn = document.getElementById('reserveBookBtn');
            var buttonDisabled = reserveBookBtn.hasAttribute('disabled');
            var swalOptions = {
                title: 'Tidak Tersedia',
                text: 'Buku ini sudah tidak tersedia untuk tempahan.',
                icon: 'error',
                confirmButtonText: 'Tutup'
            };

            if (buttonDisabled) {
                reserveBookBtn.addEventListener('click', function () {
                    Swal.fire(swalOptions);
                });
            }
        });
    </script>

</body>
</html>
