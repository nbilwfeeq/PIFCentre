<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the isbn from the POST request
    $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;

    if ($isbn) {
        // Fetch the existing row based on isbn
        $fetchQuery = "SELECT * FROM books WHERE isbn = ?";
        $stmt = $connect->prepare($fetchQuery);

        if (!$stmt) {
            die("Error: Failed to prepare statement: " . $connect->error);
        }

        $stmt->bind_param('s', $isbn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the original data
            $originalData = $result->fetch_assoc();

            // Prepare insert query
            $insertQuery = "INSERT INTO books (judul_buku, pengarang_buku, penerbit_buku, tempat_terbit, tahun_terbit, mukasurat_buku, id_jenisBuku, id_kategoriBuku, noPerolehan_buku, noPanggil_buku, isbn, lokasi_buku, bahasa_buku, gambar_buku, kuantiti_buku)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and execute the insert statement
            $insertStmt = $connect->prepare($insertQuery);

            if (!$insertStmt) {
                die("Error: Failed to prepare insert statement: " . $connect->error);
            }

            // Default quantity value is not passed, so it's automatically 1 in the database
            $defaultQuantity = 1;

            // Bind the parameters from the original data
            $insertStmt->bind_param(
                'ssssiiisssssssi',
                $originalData['judul_buku'],
                $originalData['pengarang_buku'],
                $originalData['penerbit_buku'],
                $originalData['tempat_terbit'],
                $originalData['tahun_terbit'],
                $originalData['mukasurat_buku'],
                $originalData['id_jenisBuku'],
                $originalData['id_kategoriBuku'],
                $originalData['noPerolehan_buku'],
                $originalData['noPanggil_buku'],
                $originalData['isbn'],
                $originalData['lokasi_buku'],
                $originalData['bahasa_buku'],
                $originalData['gambar_buku'],
                $defaultQuantity
            );

            // Debug to check the value of gambar_buku
            var_dump($originalData['gambar_buku']);  // Ensure it has the correct value

            // Execute the insert query
            if ($insertStmt->execute()) {
                header('Location: ../books.php?status=addedQuantity');
                exit; 
            } else {
                // Handle errors
                $message = "Error: Failed to insert data. " . $insertStmt->error;
            }

            $insertStmt->close();

        } else {
            $message = "Error: No book found with the provided ISBN.";
        }

        $stmt->close();
    } else {
        $message = "Error: Invalid ISBN provided.";
    }
} else {
    $message = "Error: Invalid request method.";
}

$connect->close();

// Optionally, if needed, you can store the message in the session for later use
// session_start();
// $_SESSION['message'] = $message;

// You can optionally add a redirect if you want to go back to the books page, e.g.
// header('Location: books.php?message=' . urlencode($message));
?>
