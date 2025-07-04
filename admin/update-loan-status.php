<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

// Check if the 'id_loan' and 'status' parameters are passed via GET
if (isset($_GET['id_loan']) && isset($_GET['status'])) {
    $id_loan = (int)$_GET['id_loan'];
    $status = (int)$_GET['status'];

    // Validate the status (only allow 0, 1, or 2 as valid statuses)
    if ($status === 0 || $status === 1 || $status === 2) {
        // Prepare the SQL query to update the loan status
        if ($status === 2) {
            // If the status is being set to 2 (Returned), no further fines should be added
            $query = "UPDATE loans SET status_pinjaman = ?, fine = 0 WHERE id_loan = ?";
        } elseif ($status === 1) {
            // If the status is being set to 1 (Buku Dituntut), just update the status without clearing the fine
            $query = "UPDATE loans SET status_pinjaman = ? WHERE id_loan = ?";
        } else {
            // For status 0 (Cancelled), just update the status without clearing fine
            $query = "UPDATE loans SET status_pinjaman = ? WHERE id_loan = ?";
        }

        // Prepare the statement
        if ($stmt = $connect->prepare($query)) {
            // Bind parameters: 'ii' indicates two integer parameters
            $stmt->bind_param('ii', $status, $id_loan);

            // Execute the query
            if ($stmt->execute()) {
                // Determine the notification info based on the status
                if ($status === 2) {
                    $info = "Pinjaman anda telah selesai dan buku telah dipulangkan. Terima kasih!";
                } elseif ($status === 1) {
                    $info = "Tempahan anda telah selesai. Sila pulangkan buku pada tarikh yang ditetapkan, Terima kasih!";
                } else {
                    $info = "Pinjaman anda telah dibatalkan. Sila hubungi pihak perpustakaan untuk maklumat lanjut.";
                }

                $info_date = date("Y-m-d"); // Current date
                $info_time = date("H:i:s"); // Current time
                $is_read = 0;

                // Fetch `nokp` related to this loan
                $fetchNokpQuery = "SELECT nokp FROM loans WHERE id_loan = ?";
                if ($fetchStmt = $connect->prepare($fetchNokpQuery)) {
                    $fetchStmt->bind_param("i", $id_loan);
                    $fetchStmt->execute();
                    $result = $fetchStmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $nokp = $row['nokp'];

                        // Insert a new notification for the status
                        $insertNotificationQuery = "INSERT INTO notifications (id_loan, nokp, info, info_date, info_time, is_read) VALUES (?, ?, ?, ?, ?, ?)";
                        if ($insertStmt = $connect->prepare($insertNotificationQuery)) {
                            $insertStmt->bind_param('isssss', $id_loan, $nokp, $info, $info_date, $info_time, $is_read);

                            if ($insertStmt->execute()) {
                                // Redirect back with a success message if notification is inserted
                                header('Location: loans.php?message=Status updated and notification inserted successfully');
                                exit();
                            } else {
                                echo "Error inserting notification: " . $insertStmt->error;
                            }

                            $insertStmt->close();
                        } else {
                            echo "Error preparing notification insert statement.";
                        }
                    } else {
                        echo "Failed to fetch nokp for loan.";
                    }

                    $fetchStmt->close();
                } else {
                    echo "Error preparing nokp fetch statement.";
                }
            } else {
                echo "Error updating status: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement.";
        }
    } else {
        echo "Invalid status value.";
    }
} else {
    echo "Required parameters missing.";
}

$connect->close();
?>
