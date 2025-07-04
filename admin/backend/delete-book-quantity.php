<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the isbn from the POST request
    $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;

    if ($isbn) {
        // Delete one row for the given isbn
        $deleteQuery = "DELETE FROM books WHERE isbn = ? LIMIT 1";  // Use LIMIT 1 to delete only one row
        $deleteStmt = $connect->prepare($deleteQuery);

        if (!$deleteStmt) {
            die("Error: Failed to prepare delete statement: " . $connect->error);
        }

        $deleteStmt->bind_param('s', $isbn);
        if ($deleteStmt->execute()) {
            // Redirect with success message
            header('Location: ../books.php?status=deletedQuantity');
        } else {
            $message = "Error: Failed to delete the book.";
        }
        $deleteStmt->close();
    } else {
        $message = "Error: Invalid ISBN provided.";
    }
} else {
    $message = "Error: Invalid request method.";
}

$connect->close();
?>
