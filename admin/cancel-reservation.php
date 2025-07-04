<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the reservation ID from the POST request
    $id_reservation = intval($_POST['id_reservation']);

    // Start a transaction
    $connect->begin_transaction();

    try {
        // Fetch the book id and quantity related to the reservation
        $stmt = $connect->prepare("SELECT id_buku FROM reservations WHERE id_reservation = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $connect->error);
        }

        $stmt->bind_param("i", $id_reservation);
        $stmt->execute();
        $reservation = $stmt->get_result()->fetch_assoc();

        if ($reservation) {
            $id_buku = $reservation['id_buku'];

            // Update the book quantity back by 1
            $stmt = $connect->prepare("UPDATE books SET kuantiti_buku = kuantiti_buku + 1 WHERE id_buku = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $connect->error);
            }

            $stmt->bind_param("i", $id_buku);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            // Delete the reservation
            $stmt = $connect->prepare("DELETE FROM reservations WHERE id_reservation = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $connect->error);
            }

            $stmt->bind_param("i", $id_reservation);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            // Optionally, delete the corresponding loan record if it exists
            $stmt = $connect->prepare("DELETE FROM loans WHERE id_buku = ? AND id_reservation = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $connect->error);
            }

            $stmt->bind_param("ii", $id_buku, $id_reservation);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            // Commit the transaction
            $connect->commit();

            // Send a success response
            echo json_encode(['success' => true, 'message' => 'Reservation and associated loan cancelled successfully.']);
        } else {
            throw new Exception("Reservation not found.");
        }
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $connect->rollback();
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
