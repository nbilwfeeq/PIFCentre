<?php
session_start();
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reservation = $_POST['id_reservation'];

    // Begin a transaction
    $connect->begin_transaction();

    try {
        // Step 1: Fetch the book details and nokp related to this reservation
        $stmt = $connect->prepare("SELECT isbn, id_buku, nokp FROM reservations WHERE id_reservation = ?");
        $stmt->bind_param("i", $id_reservation);
        $stmt->execute();
        $reservation = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$reservation) {
            throw new Exception("Reservation not found.");
        }

        $isbn = $reservation['isbn'];
        $id_buku = $reservation['id_buku'];
        $nokp = $reservation['nokp'];

        // Step 2: Increase the book quantity back by 1 for the specific book reserved
        $stmt = $connect->prepare("UPDATE books SET kuantiti_buku = kuantiti_buku + 1 WHERE id_buku = ?");
        $stmt->bind_param("i", $id_buku);
        $stmt->execute();
        $stmt->close();

        // Step 3: Remove the reservation
        $stmt = $connect->prepare("DELETE FROM reservations WHERE id_reservation = ?");
        $stmt->bind_param("i", $id_reservation);
        $stmt->execute();
        $stmt->close();

        // Step 4: Remove the loan associated with the reservation
        $stmt = $connect->prepare("DELETE FROM loans WHERE id_reservation = ?");
        $stmt->bind_param("i", $id_reservation);
        $stmt->execute();
        $stmt->close();

        // Insert notification into notifications table
        $info = "Tempahan anda telah berjaya dibatalkan.";
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

        $stmt->close();

        // Commit the transaction
        $connect->commit();

        // Redirect to the reservations page with a success message
        header('Location: ../reservations.php?status=cancel_success');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $connect->rollback();
        
        // Redirect to the reservations page with an error message
        header('Location: ../reservations.php?status=cancel_failed');
        exit;
    }
}
?>
