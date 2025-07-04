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

// Get records info
$stmt = $connect->prepare("SELECT * FROM records");
$stmt->execute();
$recordsResult = $stmt->get_result();
$records = $recordsResult->fetch_all(MYSQLI_ASSOC);

$page = 'Rekod Pinjaman Buku | Ibnu Firnas Knowledge Centre';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Ibnu Firnas Knowledge Centre (PIF Centre)">
    <meta name="description" content="Sistem Pengurusan Data dan Pinjaman Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">
    
    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--==============Datatables==============-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">

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
        /* Custom Pagination Buttons */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: black !important; /* Default button color */
            background-color: transparent; /* Transparent background */
            border: 1px solid #ddd; /* Border for pagination buttons */
            border-radius: 4px; /* Rounded corners */
            margin: 2px; /* Space between buttons */
            padding: 5px 10px; /* Padding for a comfortable click area */
            transition: all 0.3s ease; /* Smooth hover effect */
        }

        /* Hover and Active State */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important; /* Text color on hover */
            background-color: var(--cedar); /* Change this to match your theme color */
            border: none; /* Remove border on hover */
        }

        /* Current Page Button */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: white !important; /* White text */
            background-color: var(--cedar); /* Bootstrap primary color or your preferred color */
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Optional shadow for active button */
        }
    </style>
</head>

<body>

    <?php include('includes/topbar.php'); ?>

    <div class="container mt-4">
        <h1>Rekod Pinjaman Buku</h1>
        <div class="hr">
            <hr>
        </div>

        <div class="table-responsive">
            <table id="recordsTable" class="table">
                <thead>
                    <tr>
                        <th>Bil</th>
                        <th>Id Pinjaman</th>
                        <th>Nama Penuh</th>
                        <th>Judul Buku</th>
                        <th>ISBN</th>
                        <th>Tarikh Tempahan</th>
                        <th>Tarikh Pemulangan</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $index++; ?></td>
                            <td><?php echo $record['id_loan']; ?></td>
                            <td><?php echo htmlspecialchars($record['nama_penuh']); ?></td>
                            <td><?php echo htmlspecialchars($record['judul_buku']); ?></td>
                            <td><?php echo htmlspecialchars($record['isbn']); ?></td>
                            <td><?php echo $record['reserve_date']; ?></td>
                            <td><?php echo $record['return_date']; ?></td>
                            <td>RM <?php echo $record['fine']; ?></td>
                            <td>
                                <?php if ($record['status_payment'] == 1): ?>
                                    <span class="badge rounded-pill bg-success">Pembayaran Selesai</span>
                                <?php elseif ($record['fine'] > 0): ?>
                                    <span class="badge rounded-pill bg-danger">Belum Selesai</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary">Tiada Denda</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $record['payment_type']; ?></td>
                            <td>
                                <?php if ($record['fine'] > 0 && $record['status_payment'] == 0): ?>
                                    <button class=""></button>
                                <?php elseif ($record['fine'] > 0 && $record['status_payment'] == 1): ?>
                                    <?php if (!empty($record['payment_proof'])): ?>
                                        <a href="../images/payment_proof/<?php echo htmlspecialchars($record['payment_proof']); ?>" class="btn btn-primary btn-sm" target="_blank" title="Lihat Bukti Pembayaran"><i class='bx bxs-file-image'></i></a>
                                    <?php else: ?>
                                        <span class=""></span>
                                    <?php endif; ?>
                                <?php elseif ($record['fine'] > 0 && $record['status_payment'] == 2): ?>
                                    <button class="btn btn-success btn-sm check-button" data-id="<?php echo $record['id_record']; ?>" title="Sahkan Pembayaran">
                                        <i class='bx bx-check'></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-danger btn-sm delete-button" data-id="<?php echo $record['id_record']; ?>" title="Padam">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
        $(document).ready(function () {
            $('#recordsTable').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Paparkan _MENU_ rekod per halaman",
                    info: "Paparkan _START_ hingga _END_ dari _TOTAL_ rekod",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Seterusnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
    
    <script>
        $(document).on('click', '.delete-button', function () {
            var recordId = $(this).data('id'); // Ambil ID rekod

            Swal.fire({
                title: 'Adakah anda pasti?',
                text: "Rekod ini akan dipadam secara kekal!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, padamkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hantar permintaan ke backend
                    $.ajax({
                        url: 'backend/delete-record.php',
                        type: 'POST',
                        data: { id_record: recordId },
                        success: function (response) {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire('Dipadam!', res.message, 'success').then(() => {
                                    location.reload(); // Muat semula halaman
                                });
                            } else {
                                Swal.fire('Ralat!', res.message || 'Gagal memadam rekod.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Ralat!', 'Gagal menghantar permintaan.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.check-button', function () {
            var recordId = $(this).data('id'); // Ambil ID rekod

            Swal.fire({
                title: 'Sahkan Pembayaran?',
                text: "Tindakan ini akan menandakan pembayaran selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, sahkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hantar permintaan ke backend
                    $.ajax({
                        url: 'backend/update-payment-status.php',
                        type: 'POST',
                        data: { id_record: recordId },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Berjaya!', 'Status pembayaran telah disahkan.', 'success').then(() => {
                                    location.reload(); // Muat semula halaman
                                });
                            } else {
                                Swal.fire('Ralat!', response.message || 'Gagal mengesahkan status pembayaran.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Ralat!', 'Gagal menghantar permintaan.', 'error');
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>
