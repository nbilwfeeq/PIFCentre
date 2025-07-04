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

//set nokp to user_id
$nokp = $_SESSION['user_id'];

// get user info
$stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

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

// Get loan info for the logged-in user
$stmt = $connect->prepare("SELECT * FROM records WHERE nokp = ?");
$stmt->bind_param("s", $nokp);
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
    <meta name="description" content="Sistem Pengurusan Data dan Tempahan Buku Perpustakaan Ibnu Firnas, Kolej Vokasional Kuala Selangor">
    
    <link rel="shortcut icon" href="images/pif-icon-white.png" type="image/x-icon">

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/dashboard-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/banner-style.css?v=<?php echo time(); ?>">

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
        .welcome-container {
            display: flex;
            align-items: center;
        }

        .badge {
            font-size: 15px;
        }
    </style>
</head>
<body> 

<?php include "includes/sidebar.php" ?>
    
<div class="container mt-5 height-100">
    <br>
    <h1>Rekod Pinjaman Anda</h1>
    <div class="welcome-container">
        <p><?php echo htmlspecialchars($user['nama_penuh']); ?> - <?php echo htmlspecialchars($user['program']); ?></p>
    </div>
    <br>

    <div class="table-responsive">
        <table id="recordsTable" class="table">
            <thead>
                <tr>
                    <th>Bil</th>
                    <th>ID Pinjaman</th>
                    <th>Judul Buku</th>
                    <th>ISBN</th>
                    <th>Tarikh Tempahan</th>
                    <th>Tarikh Pemulangan</th>
                    <th>Denda</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Tiada pinjaman yang dibuat.</td>
                    </tr>
                <?php else: ?>
                    <?php $index = 1; ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo $index++; ?></td>
                            <td><?php echo $record['id_loan']; ?></td>
                            <td><?php echo htmlspecialchars($record['judul_buku']); ?></td>
                            <td><?php echo htmlspecialchars($record['isbn']); ?></td>
                            <td><?php echo $record['reserve_date']; ?></td>
                            <td><?php echo $record['return_date']; ?></td>
                            <td>RM <?php echo number_format($record['fine'], 2); ?></td>
                            <td><?php if ($record['status_payment'] == 1): ?>
                                    <span class="badge rounded-pill bg-success">Pembayaran Selesai</span>
                                <?php elseif ($record['fine'] > 0): ?>
                                    <span class="badge rounded-pill bg-danger">Belum Selesai</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary">Tiada Denda</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($record['fine'] > 0 && $record['status_payment'] == 0): ?>
                                    <button class="btn btn-primary btn-sm bayarButton" data-id="<?php echo $record['id_record']; ?>" data-fine="<?php echo $record['fine']; ?>">Bayar Denda</button>
                                <?php elseif ($record['fine'] > 0 && $record['status_payment'] == 1): ?>
                                    <?php if (!empty($record['payment_proof'])): ?>
                                        <a href="images/payment_proof/<?php echo htmlspecialchars($record['payment_proof']); ?>" class="btn btn-primary btn-md" target="_blank">Lihat bukti pembayaran  <?php echo htmlspecialchars($record['payment_type']) ?></a>
                                    <?php else: ?>
                                        <span class="badge text-black">Pembayaran selesai secara tunai</span>
                                    <?php endif; ?>
                                <?php elseif ($record['fine'] > 0 && $record['status_payment'] == 2): ?>
                                    <span class="badge bg-warning text-black">Menunggu pengesahan pembayaran tunai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Bayar -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Bayar Denda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" enctype="multipart/form-data" method="post" action="backend/process-payment.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <h5>Jumlah Bayaran Denda : RM <?php echo number_format($record['fine'], 2); ?></h5>
                            <br>
                            <label for="paymentType" class="form-label">Jenis Pembayaran</label>
                            <select class="form-select" id="paymentType" name="payment_type" required>
                                <option value="CASH">Tunai (CASH)</option>
                                <option value="TNG">Touch 'n Go (TNG)</option>
                            </select>
                        </div>

                        <!-- Conditional TNG Section -->
                        <div id="tngSection" style="display: none;">
                            <p>Sila imbas kod QR untuk pembayaran:</p>
                            <img src="images/qr-code.jpg" alt="QR Code" class="img-fluid" />
                            <a href="images/qr-code.jpg" download class="btn btn-link">Muat Turun QR Code</a>
                        </div>

                        <!-- Payment Proof Section (Visible only for TNG) -->
                        <div id="paymentProofSection" style="display: none;">
                            <div class="mb-3">
                                <label for="paymentProof" class="form-label">Bukti Pembayaran</label>
                                <input class="form-control" type="file" id="paymentProof" name="payment_proof" accept="image/png, image/jpeg">
                                <div class="form-text">Format fail: PNG/JPG/JPEG sahaja.</div>
                            </div>
                        </div>

                        <input type="hidden" name="record_id" id="recordIdInput">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Hantar Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function () {
        const paymentTypeSelect = document.getElementById('paymentType');
        const paymentProofField = document.getElementById('paymentProof');
        const paymentProofSection = document.getElementById('paymentProofSection');
        const tngSection = document.getElementById('tngSection');

        // Initially hide or show sections based on selected payment type
        if (paymentTypeSelect.value === 'TNG') {
            tngSection.style.display = 'block';
            paymentProofSection.style.display = 'block';
            paymentProofField.required = true; // Make proof required for TNG
        } else {
            tngSection.style.display = 'none';
            paymentProofSection.style.display = 'none';
            paymentProofField.required = false; // Don't require proof for CASH
        }

        // Listen for changes in payment type and update fields accordingly
        paymentTypeSelect.addEventListener('change', function () {
            if (this.value === 'TNG') {
                tngSection.style.display = 'block';
                paymentProofSection.style.display = 'block';
                paymentProofField.required = true; // Make proof required for TNG
            } else {
                tngSection.style.display = 'none';
                paymentProofSection.style.display = 'none';
                paymentProofField.required = false; // Don't require proof for CASH
            }
        });

        // Pass record ID and fine amount to the modal
        const bayarButtons = document.querySelectorAll('.bayarButton');
        bayarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const recordId = this.getAttribute('data-id');
                const fine = this.getAttribute('data-fine');
                document.getElementById('recordIdInput').value = recordId;

                // Update fine amount in the modal
                document.querySelector('.modal-body h5').textContent = `Jumlah Bayaran Denda : RM ${parseFloat(fine).toFixed(2)}`;

                const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                paymentModal.show();
            });
        });
    });
</script>

</body>

</html>
