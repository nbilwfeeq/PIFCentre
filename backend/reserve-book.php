<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';
include '../session/security.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data directly from POST request
    $nama_penuh = $_POST['nama_penuh'];
    $judul_buku = $_POST['judul_buku'];
    $isbn = $_POST['isbn'];
    $gambar_buku = $_POST['gambar_buku'];
    $reserve_date = $_POST['reserve_date'];
    $return_date = $_POST['return_date'];

    // Redirect if reserve_date or return_date is empty
    if (empty($reserve_date) || empty($return_date)) {
        header('Location: ../book-detail.php?id=' . $id_buku . '&status=chooseDate');
        exit;
    }

    // Set nokp to user_id from session
    $nokp = $_SESSION['user_id'];

    // Set default status_tempahan as 1
    $status_tempahan = 1;

    // Start a transaction
    $connect->begin_transaction();

    try {
        // Check availability of books with the same isbn
        $stmt = $connect->prepare("
            SELECT id_buku, id_jenisBuku, id_kategoriBuku, kuantiti_buku
            FROM books
            WHERE isbn = ? AND kuantiti_buku > 0
            LIMIT 1
        ");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $connect->error);
        }

        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            $id_buku = $result['id_buku']; // Specific book ID to reserve
            $id_jenisBuku = $result['id_jenisBuku'];
            $id_kategoriBuku = $result['id_kategoriBuku'];
            $currentQuantity = $result['kuantiti_buku'];

            if ($currentQuantity > 0) {
                // Insert reservation into reservations table
                $stmt = $connect->prepare("
                    INSERT INTO reservations (nama_penuh, nokp, id_buku, judul_buku, id_kategoriBuku, id_jenisBuku, isbn, gambar_buku, reserve_date, return_date, status_tempahan)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $connect->error);
                }

                $stmt->bind_param("ssissssssss", $nama_penuh, $nokp, $id_buku, $judul_buku, $id_kategoriBuku, $id_jenisBuku, $isbn, $gambar_buku, $reserve_date, $return_date, $status_tempahan);
                
                if (!$stmt->execute()) {
                    throw new Exception("Execute statement failed: " . $stmt->error);
                }

                // Fetch the ID of the newly created reservation
                $id_reservation = $connect->insert_id;

                // Insert notification into notifications table
                $info = "Tempahan anda telah berjaya, sila tunggu pengesahan.";
                $info_date = date("Y-m-d"); // Current date
                $info_time = date("H:i:s"); // Current time
                $is_read = 0;
                $stmt = $connect->prepare("
                    INSERT INTO notifications (id_reservation, nokp, info, info_date, info_time, is_read)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $connect->error);
                }

                $stmt->bind_param("isssss", $id_reservation, $nokp, $info, $info_date, $info_time, $is_read);
                if (!$stmt->execute()) {
                    throw new Exception("Execute statement failed: " . $stmt->error);
                }

                // Insert notification into notifications_admin table
                $info = "Pengguna telah membuat tempahan.";
                $info_date;
                $info_time;
                $is_read = 0;
                $stmt = $connect->prepare("
                    INSERT INTO notifications_admin (id_reservation, info, info_date, info_time, is_read)
                    VALUES (?, ?, ?, ?, ?)
                ");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $connect->error);
                }

                $stmt->bind_param("issss", $id_reservation, $info, $info_date, $info_time, $is_read);
                if (!$stmt->execute()) {
                    throw new Exception("Execute statement failed: " . $stmt->error);
                }

                // Update the quantity of the specific book reserved
                $stmt = $connect->prepare("
                    UPDATE books
                    SET kuantiti_buku = kuantiti_buku - 1
                    WHERE id_buku = ?
                ");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $connect->error);
                }

                $stmt->bind_param("i", $id_buku);
                if (!$stmt->execute()) {
                    throw new Exception("Execute statement failed: " . $stmt->error);
                }

                // Commit the transaction
                $connect->commit();

                // Redirect with success message
                header('Location: ../book-detail.php?id=' . $id_buku . '&status=reserved');
                exit;
            } else {
                throw new Exception("No available books to reserve.");
            }
        } else {
            throw new Exception("No books available with the given call number.");
        }
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $connect->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
    exit;
}

?>
