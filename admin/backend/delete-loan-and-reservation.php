<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

// Check if required parameters are present
if (isset($_GET['id_loan']) && isset($_GET['id_buku']) && isset($_GET['id_reservation'])) {
    $id_loan = $_GET['id_loan'];
    $id_buku = $_GET['id_buku'];
    $id_reservation = $_GET['id_reservation'];

    // Begin transaction
    $connect->begin_transaction();

    try {
        // Fetch required details for records table
        $fetchDetailsQuery = "
            SELECT 
                r.id_reservation, r.nokp, r.reserve_date, r.return_date, 
                b.judul_buku, b.isbn, b.id_buku, 
                u.nama_penuh, l.fine, l.id_loan 
            FROM reservations r
            JOIN books b ON r.id_buku = b.id_buku
            JOIN loans l ON r.id_reservation = l.id_reservation
            JOIN user u ON r.nokp = u.nokp
            WHERE r.id_reservation = ? AND l.id_loan = ? AND b.id_buku = ?
        ";
        $stmt = $connect->prepare($fetchDetailsQuery);
        $stmt->bind_param('iii', $id_reservation, $id_loan, $id_buku);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("No matching loan, reservation, or book details found.");
        }

        $record = $result->fetch_assoc();

        // Insert the data into the records table
        $insertRecordQuery = "
            INSERT INTO records 
            (id_reservation, id_loan, nama_penuh, nokp, id_buku, judul_buku, isbn, reserve_date, return_date, fine) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $connect->prepare($insertRecordQuery);
        $stmt->bind_param(
            'iississssd',
            $record['id_reservation'],
            $record['id_loan'],
            $record['nama_penuh'],
            $record['nokp'],
            $record['id_buku'],
            $record['judul_buku'],
            $record['isbn'],
            $record['reserve_date'],
            $record['return_date'],
            $record['fine']
        );
        $stmt->execute();

        // Delete the loan record with the matching reservation ID
        $deleteLoanQuery = "DELETE FROM loans WHERE id_loan = ? AND id_reservation = ?";
        $stmt = $connect->prepare($deleteLoanQuery);
        $stmt->bind_param('ii', $id_loan, $id_reservation);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Loan with the given ID and reservation ID not found.");
        }

        // Delete the reservation record with the matching reservation ID and book ID
        $deleteReservationQuery = "DELETE FROM reservations WHERE id_buku = ? AND id_reservation = ?";
        $stmt = $connect->prepare($deleteReservationQuery);
        $stmt->bind_param('ii', $id_buku, $id_reservation);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Reservation with the given book ID and reservation ID not found.");
        }

        // Update the quantity of the book back to 1
        $updateQuantityQuery = "UPDATE books SET kuantiti_buku = kuantiti_buku + 1 WHERE id_buku = ?";
        $stmt = $connect->prepare($updateQuantityQuery);
        $stmt->bind_param('i', $id_buku);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to update book quantity.");
        }

        // Commit the transaction if all operations were successful
        $connect->commit();

        // Redirect to loans.php after successful deletion
        header('Location: ../loans.php');
        exit; // Ensure no further code is executed after redirection
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $connect->rollback();
        echo "Failed to process the request: " . $e->getMessage();
    }

    $stmt->close();
    $connect->close();
} else {
    echo "Required parameters missing.";
}
?>
