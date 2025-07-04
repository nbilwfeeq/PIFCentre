<?php
include '../session/security.php';
include '../database/config.php';

if (isset($_POST["Import"])) {
    if (isset($_FILES["file"])) {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");

        if (!$handle) {
            header("Location: ../books-import.php?status=fileError");
            exit();
        }

        // Default image name
        $gambar_buku = 'default-book.png';

        // Path where the default file should be stored
        $uploadFileDir = '../../images/books/';
        $defaultFilePath = $uploadFileDir . $gambar_buku;

        // Check if the default file exists in the directory; if not, upload it
        if (!file_exists($defaultFilePath)) {
            $defaultSourcePath = '../images/default-book.png'; // Source of the default image
            if (!copy($defaultSourcePath, $defaultFilePath)) {
                echo 'Failed to copy the default image to the books folder.';
                exit;
            }
        }

        // Get additional data from the form
        $kuantiti_buku = (int)$_POST['kuantiti_buku']; // Default quantity for books

        // Start transaction
        $connect->begin_transaction();

        try {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Replace empty or null values with '-'
                foreach ($data as $key => $value) {
                    if (empty($value)) {
                        $data[$key] = '-';
                    }
                }

                $stmt = $connect->prepare(
                    "INSERT INTO books (judul_buku, pengarang_buku, noPanggil_buku, penerbit_buku, tempat_terbit, tahun_terbit, mukasurat_buku, isbn, id_jenisBuku, id_kategoriBuku, noPerolehan_buku, lokasi_buku, bahasa_buku, gambar_buku, kuantiti_buku) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
                );

                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $connect->error);
                }

                // Bind the parameters, including `gambar_buku` and `kuantiti_buku`
                $stmt->bind_param(
                    "sssssiisiissssi", 
                    $data[0],         // judul_buku
                    $data[1],         // pengarang_buku
                    $data[2],         // noPanggil_buku
                    $data[3],         // penerbit_buku
                    $data[4],         // tempat_terbit
                    $data[5],         // tahun_terbit
                    $data[6],         // mukasurat_buku
                    $data[7],         // isbn
                    $data[8],         // id_jenisBuku
                    $data[9],         // id_kategoriBuku
                    $data[10],        // noPerolehan_buku
                    $data[11],        // lokasi_buku
                    $data[12],        // bahasa_buku
                    $gambar_buku,     // gambar_buku (set to 'default-book.png')
                    $kuantiti_buku    // kuantiti_buku (from form)
                );

                // Execute the statement
                if (!$stmt->execute()) {
                    throw new Exception("Failed to execute query: " . $stmt->error);
                }

                $stmt->close();
            }

            // Commit the transaction if everything is successful
            $connect->commit();

            fclose($handle);
            header("Location: ../books-import.php?status=imported");
            exit();
        } catch (Exception $e) {
            // Rollback on error
            $connect->rollback();

            // Log the error or print it for debugging purposes
            error_log("Import Error: " . $e->getMessage());
            fclose($handle);
            header("Location: ../books-import.php?status=critical");
            exit();
        }
    } else {
        header("Location: ../books-import.php?status=noFile");
        exit();
    }
} else {
    header("Location: ../books-import.php?status=invalidRequest");
    exit();
}
?>
