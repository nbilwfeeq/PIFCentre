<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database/config.php';
include 'session/security.php';

// get user info
$stmt = $connect->prepare("SELECT * FROM user");
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_all(MYSQLI_ASSOC);

// Get reservation info
$stmt = $connect->prepare("SELECT * FROM reservations");
$stmt->execute();
$reservationsResult = $stmt->get_result();
$reservations = $reservationsResult->fetch_all(MYSQLI_ASSOC);

$page = 'Senarai Tempahan Buku | Ibnu Firnas Knowledge Centre';

$jenisBukuMapping = [
    '1' => 'SEJARAH',
    '2' => 'PENGAJIAN ISLAM',
    '3' => 'BM',
    '4' => 'BI',
    '5' => 'MATEMATIK',
    '6' => 'SAINS',
    '7' => 'UMUM',
    '8' => 'KULINARI',
    '9' => 'BAKERI',
    '10' => 'PEMASARAN',
    '11' => 'PERAKAUNAN',
    '12' => 'ANIMASI',
    '13' => 'WEB'
];

$kategoriBukuMapping = [
    '1' => 'VOKASIONAL',
    '2' => 'AKADEMIK'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">
    
    <!-- ==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">


    <title><?php echo $page; ?></title>

    <style>
        .btn-custom {
            background-color: transparent;
            border: 2px solid black; 
            color: black;
            transition: all 0.1s ease; 
        }

        .btn-custom:hover {
            color: #fff;
            background-color: var(--orange); 
            border: none;
        }

        .filter-container {
            margin-bottom: 20px;
        }
    </style>

</head>

<body>

    <?php include('includes/topbar.php'); ?>

    <div class="content container p-3">
        <h1>Senarai Tempahan Buku</h1>
        <div class="hr">
            <hr>
        </div>

        <!-- <div class='p-3'>
            <button class="btn btn-custom" onclick="window.location='create-reservation.php'"><i class='bx bxs-bookmark'></i>&nbspTambah Tempahan</button>
            <button class="btn btn-secondary" onclick="window.location='#'"><i class='bx bx-cloud-upload'></i>&nbspMuat Naik Data .CSV</button>
        </div> -->
        
        <br>
        <div class="filter-container d-flex justify-content-between align-items-center mb-3">
            <div class="search-bar d-flex align-items-center">
                Show
                <select id="entriesPerPage" class="form-select entries-select ms-2">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entries
            </div>
            <div class="filter-selects d-flex align-items-center">
                <input type="text" id="searchBar" class="form-control me-2 flex-grow-1" placeholder="Cari Tempahan...">
                <select id="filterStatusTempahan" class="form-select">
                    <option value="">--Status Tempahan--</option>
                    <option value="1">Menunggu Pengesahan</option>
                    <option value="2">Tempahan Disahkan</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" id="reservationData">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nama Pengguna</th>
                        <th>Judul Buku</th>
                        <th>Tarikh Tempahan</th>
                        <th>Tarikh Pemulangan</th>
                        <th>Status Tempahan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows populated by JavaScript -->
                </tbody>
            </table>
        </div>
        
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toastMessage" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                <!-- Message content will be inserted here -->
            </div>
        </div>
    </div>

    <!--=============== Bootstrap 5.2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <!--=============== jQuery ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!--=============== Datatables ===============-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!--=============== SweetAlert2 ===============-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function showToast(message, isSuccess) {
            var toastElement = document.getElementById('toastMessage');
            var toastBody = document.getElementById('toastBody');
            
            // Set the toast message and style
            toastBody.textContent = message;
            toastElement.classList.remove('bg-success', 'bg-danger');
            toastElement.classList.add(isSuccess ? 'bg-success' : 'bg-danger');

            // Initialize the toast and show it
            var toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        function cancelReservation(id_reservation) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cancel-reservation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast(response.message, true);
                        location.reload(); // Reloads the page to reflect changes
                    } else {
                        showToast(response.message, false);
                    }
                }
            };
            xhr.send("id_reservation=" + id_reservation);
        }

        function updateStatus(id, status) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update-status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showToast(response.message, true);
                        location.reload(); // Reloads the page to reflect changes
                    } else {
                        showToast(response.message, false);
                    }
                }
            };
            xhr.send("id_reservation=" + id + "&status_tempahan=" + status);
        }
    </script>

    <script>
        $(document).ready(function () {
            // Initialize the DataTable
            let table = $('#reservationData').DataTable({
                paging: false,
                searching: false
            });

            // Load table data
            function loadTable(search = '', status = '', entries = 10) {
                $.ajax({
                    url: 'backend/get-reservations.php',
                    type: 'GET',
                    data: { search, stat: status, entries },
                    success: function (data) {
                        // Clear the table data
                        table.clear().draw();

                        // Append the new data
                        $('#reservationData tbody').html(data);

                        // Reinitialize the DataTable
                        table.rows.add($(data)).draw();
                    }
                });
            }

            // Initial load
            loadTable();

            // Real-time search
            $('#searchBar').on('keyup', function () {
                let search = $(this).val();
                let status = $('#filterStatusTempahan').val();
                let entries = $('#entriesPerPage').val();
                loadTable(search, status, entries);
            });

            // Status Tempahan filter
            $('#filterStatusTempahan').on('change', function () {
                let search = $('#searchBar').val();
                let status = $(this).val();
                let entries = $('#entriesPerPage').val();
                loadTable(search, status, entries);
            });

            // Entries per page
            $('#entriesPerPage').on('change', function () {
                let search = $('#searchBar').val();
                let status = $('#filterStatusTempahan').val();
                let entries = $(this).val();
                loadTable(search, status, entries);
            });
        });
    </script>
</body>
</html>
