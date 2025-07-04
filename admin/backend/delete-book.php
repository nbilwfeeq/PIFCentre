<?php
include '../database/config.php';

// Check if the ISBN is provided
if (isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];

    // Prepare SQL query to delete all books with the same ISBN
    $deleteStmt = $connect->prepare("DELETE FROM books WHERE isbn = ?");
    $deleteStmt->bind_param("s", $isbn);

    if ($deleteStmt->execute()) {
        // Success, return a JSON response
        echo json_encode(['success' => true]);
    } else {
        // Error, return a JSON response
        echo json_encode(['success' => false, 'message' => 'Failed to delete books.']);
    }

    $deleteStmt->close();
} else {
    // If no ISBN is provided, return an error response
    echo json_encode(['success' => false, 'message' => 'ISBN not provided.']);
}

$connect->close();
?>
