<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library
require 'vendor/autoload.php';
include 'database/config.php';
include 'session/security.php';

$page = 'Tempahan & Pinjaman Buku | Ibnu Firnas Knowledge Centre';

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

$jenisBukuMapping = [
    '0' => '-',
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
    '13' => 'WEB',
    '14' => 'PTA'
];

$kategoriBukuMapping = [
    '0' => '-',
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

    <!--==================CSS=================-->
    <link rel="stylesheet" href="styles/reservations-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="styles/main-style.css?v=<?php echo time(); ?>">

    <!--===============Bootstrap 5.2==========-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!--=============== BoxIcons ===============-->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <!--=============== Google Fonts ===============-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <!--=============== DataTables ===============-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        /* Custom Table Styling */
        #reservationsTable, #loansTable {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border: none;
        }

        #reservationsTable th, #reservationsTable td, #loansTable th, #loansTable td {
            padding: 12px 15px;
            text-align: left;
            border: none;
        }

        #reservationsTable thead, #loansTable thead {
            background-color: var(--tortilla); /* Example color for header background */
            color: white;
            font-weight: bold;
        }

        #reservationsTable tbody tr:nth-child(even), #loansTable tbody tr:nth-child(even) {
            background-color: #f1f1f1; 
        }

        #reservationsTable tbody tr:hover, #loansTable tbody tr:hover {
            background-color: #f1f1f1; /* Slightly darker gray on hover */
        }

        #reservationsTable td, #loansTable td {
            font-size: 0.9em;
            color: #333; /* Dark gray text */
        }
    </style>

    <title><?php echo $page; ?></title>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="container mt-5 height-100">
        <br>
        <h1>Tempahan & Pinjaman Buku</h1>
        <p><?php echo htmlspecialchars($user['nama_penuh']); ?> - <?php echo htmlspecialchars($user['program']); ?></p>
        <?php if (empty($reservations)): ?>
            <p style="color: #666; margin-top: 40px;">Tiada tempahan yang dibuat.</p>
        <?php else: ?>
        <?php foreach ($reservations as $reservation): ?>
        <br>
        <div class="row" data-aos="fade-right">
            <div class="col-md-3">
                <img src="images/books/<?php echo htmlspecialchars($reservation['gambar_buku']); ?>" alt="<?php echo htmlspecialchars($reservation['judul_buku']); ?>">
            </div>
            <div class="col-md-4 row-body">
                <h3><b>ID<?php echo htmlspecialchars($reservation['id_reservation']); ?> - <?php echo htmlspecialchars($reservation['judul_buku']); ?></b></h3>
                <br>
                <h5>Kategori : <?php echo htmlspecialchars($kategoriBukuMapping[$reservation['id_kategoriBuku']]); ?></h5>
                <h5>Jenis : <?php echo htmlspecialchars($jenisBukuMapping[$reservation['id_jenisBuku']]); ?></h5>
                <br>
                <h5><b>Tarikh</b></h5>
                <h6>Tempahan : <?php echo htmlspecialchars($reservation['reserve_date']); ?></h6>
                <h6>Pemulangan : <?php echo htmlspecialchars($reservation['return_date']); ?></h6>
                <br>
                Status :
                <?php 
                     $canCancel = true;

                     // Find the related loan
                     $relatedLoans = array_filter($loans, function($loan) use ($reservation) {
                         return $loan['isbn'] == $reservation['isbn'];
                     });
                 
                     // If related loan exists
                     if (!empty($relatedLoans)) {
                         $loan = reset($relatedLoans); // Get the first related loan
                 
                         if ($loan['status_pinjaman'] == 1) {
                            echo 'Status: <span>Buku Dipinjam</span>';
                            echo '<br><span style="color: red; font-weight: bold;">Perhatian! Sila pulangkan buku pada tarikh yang telah ditetapkan. Atau anda akan didenda.</span>';
                            
                            // if ($loan['email_sent'] == 0) {

                            // $emailStmt = $connect->prepare("SELECT email FROM user WHERE nokp = ?");
                            // $emailStmt->bind_param("s", $nokp);
                            // $emailStmt->execute();
                            // $emailResult = $emailStmt->get_result();
                            // $emailRow = $emailResult->fetch_assoc();
                        
                            // if ($emailRow) {
                            //     $userEmail = $emailRow['email'];
                        
                            //     // Fetch book details using ISBN
                            //     $bookStmt = $connect->prepare("SELECT gambar_buku, judul_buku FROM books WHERE isbn = ?");
                            //     $bookStmt->bind_param("s", $loan['isbn']);
                            //     $bookStmt->execute();
                            //     $bookResult = $bookStmt->get_result();
                            //     $bookRow = $bookResult->fetch_assoc();
                        
                            //     if ($bookRow) {
                            //         $gambarBuku = $bookRow['gambar_buku'];
                            //         $judulBuku = $bookRow['judul_buku'];
                        
                            //         // Send email using PHPMailer
                            //         $mail = new PHPMailer(true);
                            //         try {
                            //             $mail->isSMTP();
                            //             $mail->Host = 'smtp.gmail.com';
                            //             $mail->SMTPAuth = true;
                            //             $mail->Username = 'ahmadnabilwafiq@gmail.com';
                            //             $mail->Password = 'xbnz uyng htxk xctm'; 
                            //             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            //             $mail->Port = 587;
                        
                            //             $mail->setFrom('pifcenterkvks@gmail.com', 'Perpustakaan Ibnu Firnas Knowledge Centre');
                            //             $mail->addAddress($userEmail);
                        
                            //             $mail->isHTML(true);
                            //             $mail->Subject = "PINJAMAN BUKU - $judulBuku";
                            //             $mail->Body = "
                            //                 <div style='border: 2px solid #ddd; padding: 20px; font-family: Arial, sans-serif;'>
                            //                     <h2 style='text-align: center; color: #333;'>Pinjaman Buku Anda</h2>
                            //                     <p>Berikut adalah maklumat mengenai buku yang telah anda pinjam:</p>
                            //                     <div style='display: flex; align-items: center; margin-top: 20px;'>
                            //                         <img src='https://pifkvks.com/images/books/{$gambarBuku}' alt='{$judulBuku}' style='max-width: 150px; margin-right: 20px;'>
                            //                         <div>
                            //                             <h3 style='margin: 0;'>{$judulBuku}</h3>
                            //                             <p><b>Tarikh Pemulangan:</b> " . htmlspecialchars($loan['return_date']) . "</p>
                            //                         </div>
                            //                     </div>
                            //                     <br>
                            //                     <p>Sila pastikan buku ini dipulangkan sebelum tarikh tersebut untuk mengelakkan denda.</p>
                            //                     <p style='text-align: center;'>Terima kasih kerana menggunakan sistem Ibnu Firnas Knowledge Centre.</p>
                            //                 </div>
                            //             ";
                            //             $mail->AltBody = "Pinjaman buku anda telah selesai. Sila kembalikan buku pada " . htmlspecialchars($loan['return_date']) . ".";
                        
                            //             $mail->send();
                            //             echo '<br><br>Email pemberitahuan telah dihantar ke <b>' . htmlspecialchars($userEmail) . '</b>';
                        
                            //             // Update email_sent status
                            //             $updateStmt = $connect->prepare("UPDATE loans SET email_sent = 1 WHERE id_loan = ?");
                            //             $updateStmt->bind_param("i", $loan['id_loan']);
                            //             $updateStmt->execute();
                        
                            //         } catch (Exception $e) {
                            //             echo '<br>Gagal menghantar email pemberitahuan. Error: ' . $mail->ErrorInfo;
                            //         }
                            //     }
                            // }
                            // }
                        } elseif ($loan['status_pinjaman'] == 2) {
                            // Loan has been returned
                            echo '<span style="color: green; font-weight: bold;">Buku Telah Dipulangkan</span>';
                            echo '<br><span>Terima kasih atas pinjaman anda!</span>';
                        } else {
                             // If loan is not active, show reservation status
                             if ($reservation['status_tempahan'] == 1) {
                                 echo 'Menunggu Pengesahan';
                             } elseif ($reservation['status_tempahan'] == 2) {
                                 echo 'Buku Boleh Diambil';
                                 echo '<br><br>
                                        <button class="btn btn-success btn-sm" onclick="downloadLoanForm(\'' . htmlspecialchars($loan['id_loan']) . '\')">
                                            Muat Turun Slip Pinjaman
                                        </button>';
                             }
                         }
                     } else {
                         // In case no related loan, show reservation status
                         if ($reservation['status_tempahan'] == 1) {
                             echo 'Menunggu Pengesahan';
                         } elseif ($reservation['status_tempahan'] == 2) {
                             echo 'Buku Boleh Diambil';
                         }
                     }
                 
                    
                ?>


                <br><br>

                <!-- Cancel button -->
                <?php if ($canCancel && !(isset($loan) && $loan['status_pinjaman'] == 2)): ?>
                    <form id="cancelForm-<?php echo $reservation['id_reservation']; ?>" 
                        action="backend/cancel-reservation.php" 
                        method="POST" 
                        style="display:inline;">
                        <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                        <input type="hidden" name="isbn" value="<?php echo $reservation['isbn']; ?>">
                        <button type="button" 
                                class="btn btn-danger btn-sm cancel-btn" 
                                data-form-id="cancelForm-<?php echo $reservation['id_reservation']; ?>">
                            Batal Tempahan
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="col-md-3 row-body2">
                <!-- Additional Info -->
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!--=============== jQuery ===============-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!--=============== Bootstrap JS ===============-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!--=============== DataTables JS ===============-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reservationsTable').DataTable();
            $('#loansTable').DataTable();
        });

        function downloadLoanForm(loanId) {
            Swal.fire({
                icon: 'warning',
                title: 'Muat Turun Slip Pinjaman',
                text: 'Adakah anda pasti ingin memuat turun slip pinjaman ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Muat Turun!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const downloadURL = 'form.php?id_loan=' + encodeURIComponent(loanId);
                    window.location.href = downloadURL; // Redirect to form.php with loan ID
                }
            });
        }
    </script>

    <script>
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-form-id');
                Swal.fire({
                    title: 'Adakah anda pasti?',
                    text: "Tempahan ini akan dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, batal!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });
        
        const parameter = new URLSearchParams(window.location.search);
        const getURL = parameter.get('status');

        if (getURL == 'cancel_success') {
            Swal.fire({
                icon: 'success',
                title: 'Berjaya!',
                text: 'Tempahan berjaya dibatalkan!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                iconColor: '#fff',
                color: '#fff',
            }).then(() => {
                const urlWithoutStatus = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, urlWithoutStatus);
            });
        }


    </script>


</body>
</html>

