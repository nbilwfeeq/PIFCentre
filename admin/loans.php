<?php
session_start();

ini_set('display_errors', 0);  // Turn off error display
error_reporting(E_ALL & ~E_WARNING);

include 'database/config.php';
include 'session/security.php';

// get user info
$stmt = $connect->prepare("SELECT * FROM user");
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_all(MYSQLI_ASSOC);

// Get loans info
$stmt = $connect->prepare("SELECT * FROM loans");
$stmt->execute();
$loansResult = $stmt->get_result();
$loans = $loansResult->fetch_all(MYSQLI_ASSOC);

$page = 'Senarai Pinjaman Buku | Ibnu Firnas Knowledge Centre';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Pinjaman Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
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
        <h1>Senarai Pinjaman Buku</h1>
        <div class="hr">
            <hr>
        </div>

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
                <input type="text" id="searchBar" class="form-control me-2 flex-grow-1" placeholder="Cari Pinjaman...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" id="loanData">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nama Pengguna</th>
                        <th>No. Kad Pengenalan</th>
                        <th>Judul Buku</th>
                        <th>Tarikh Pemulangan</th>
                        <th>Denda (RM)</th>
                        <th>Status Pinjaman</th>
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

        function cancelReservation(id_loan) {
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
            xhr.send("id_loan=" + id_loan);
        }

        function updateStatus(id, status) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update-loan-status.php", true);
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
            xhr.send("id_loan=" + id + "&status_pinjaman=" + status);
        }
    </script>

    <script>
        $(document).ready(function () {
            // Initialize the DataTable (without pagination and searching for now)
            let table = $('#loanData').DataTable({
                paging: false,
                searching: false,
                bInfo: false, // Disable the "Showing X of Y entries" info
                bLengthChange: false, // Disable entries per page dropdown (optional)
            });

            // Load table data function
            function loadTable(search = '', entries = 10) {
                $.ajax({
                    url: 'backend/get-loans.php',
                    type: 'GET',
                    data: { search, entries },
                    success: function (data) {
                        // Clear existing table data first
                        table.clear().draw();

                        // Append new data to the table
                        $('#loanData tbody').html(data);

                        // Reinitialize the table to apply the new data
                        table.rows.add($('#loanData tbody tr')).draw();
                    }
                });
            }

            // Initial load of the table
            loadTable();

            // Real-time search
            $('#searchBar').on('keyup', function () {
                let search = $(this).val();
                let entries = $('#entriesPerPage').val();
                loadTable(search, entries);
            });

            // Entries per page
            $('#entriesPerPage').on('change', function () {
                let search = $('#searchBar').val();
                let entries = $(this).val();
                loadTable(search, entries);
            });
        });
    </script>
</body>
</html>
