<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_reservation = $_POST['id_reservation'];
    $status_tempahan = $_POST['status_tempahan'];

    // Update status in reservations table
    $updateStmt = $connect->prepare("UPDATE reservations SET status_tempahan = ? WHERE id_reservation = ?");
    $updateStmt->bind_param("ii", $status_tempahan, $id_reservation);

    if ($updateStmt->execute()) {
        $response = ['success' => true, 'message' => 'Status updated successfully.'];

        if ($status_tempahan == 2) {
            // If status is 'Selesai' (2), insert into loans table
            $selectStmt = $connect->prepare("SELECT * FROM reservations WHERE id_reservation = ?");
            $selectStmt->bind_param("i", $id_reservation);
            $selectStmt->execute();
            $reservationData = $selectStmt->get_result()->fetch_assoc();

            if ($reservationData) {
                $id_loan = null; // Assuming auto-increment
                $nama_penuh = $reservationData['nama_penuh'];
                $nokp = $reservationData['nokp'];
                $id_buku = $reservationData['id_buku'];
                $judul_buku = $reservationData['judul_buku'];
                $isbn = $reservationData['isbn'];
                $reserve_date = $reservationData['reserve_date'];
                $return_date = $reservationData['return_date'];

                // Insert into loans table
                $insertStmt = $connect->prepare("INSERT INTO loans (id_loan, nama_penuh, nokp, id_buku, judul_buku, isbn, reserve_date, return_date, id_reservation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertStmt->bind_param("ississssi", $id_loan, $nama_penuh, $nokp, $id_buku, $judul_buku, $isbn, $reserve_date, $return_date, $id_reservation);

                if ($insertStmt->execute()) {
                    // Fetch the last inserted id_loan
                    $id_loan = $connect->insert_id;

                    // Insert notification after loan created
                    $info = "Buku anda telah disahkan, Anda boleh menuntut buku di perpustakaan.";
                    $info_date = date("Y-m-d"); // Current date
                    $info_time = date("H:i:s"); // Current time
                    $is_read = 0;
                    $insertNotificationStmt = $connect->prepare("INSERT INTO notifications (id_reservation, id_loan, nokp, info, info_date, info_time, is_read) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $insertNotificationStmt->bind_param("iisssss", $id_reservation, $id_loan, $nokp, $info, $info_date, $info_time, $is_read);

                    if ($insertNotificationStmt->execute()) {
                        $response['message'] = 'Status updated, loan created, and notification sent successfully.';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Failed to create notification: ' . $insertNotificationStmt->error;
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to create loan record: ' . $insertStmt->error;
                }
            }
        } elseif ($status_tempahan == 1) {
            // If status is reverted to 'Belum Selesai' (1), fetch reservation data to retrieve nokp
            $selectStmt = $connect->prepare("SELECT nokp FROM reservations WHERE id_reservation = ?");
            $selectStmt->bind_param("i", $id_reservation);
            $selectStmt->execute();
            $reservationData = $selectStmt->get_result()->fetch_assoc();

            if ($reservationData) {
                $nokp = $reservationData['nokp'];

                // Delete from loans table
                $deleteStmt = $connect->prepare("DELETE FROM loans WHERE id_reservation = ?");
                $deleteStmt->bind_param("i", $id_reservation);

                if ($deleteStmt->execute()) {
                    // Insert a new notification instead of updating
                    $info = "Pengesahan buku anda dibatalkan, Sila tunggu sebentar.";
                    $info_date = date("Y-m-d"); // Current date
                    $info_time = date("H:i:s"); // Current time
                    $is_read = 0;
                    $insertNotificationStmt = $connect->prepare("INSERT INTO notifications (id_reservation, nokp, info, info_date, info_time, is_read) VALUES (?, ?, ?, ?, ?, ?)");
                    $insertNotificationStmt->bind_param("issss", $id_reservation, $nokp, $info, $info_date, $info_time, $is_read);

                    if ($insertNotificationStmt->execute()) {
                        $response['message'] = 'Status reverted to Belum Selesai, loan deleted, and notification inserted.';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Failed to insert notification: ' . $insertNotificationStmt->error;
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to delete loan record: ' . $deleteStmt->error;
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to fetch reservation details for notification.';
            }
        }
    } else {
        $response = ['success' => false, 'message' => 'Failed to update status: ' . $updateStmt->error];
    }

    echo json_encode($response);
}
?>
