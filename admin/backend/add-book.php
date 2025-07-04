<?php
include '../database/config.php';
include '../session/security.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $judul_buku = strtoupper(trim($_POST['judul_buku']));
    $pengarang_buku = strtoupper(trim($_POST['pengarang_buku'])); // Pengarang Buku
    $penerbit_buku = strtoupper(trim($_POST['penerbit_buku'])); // Penerbit Buku
    $tempat_terbit = strtoupper(trim($_POST['tempat_terbit'])); // Tempat Terbit
    $tahun_terbit = intval(trim($_POST['tahun_terbit'])); // Tahun Terbit
    $mukasurat_buku = intval(trim($_POST['mukasurat_buku'])); // Jumlah Muka Surat
    $id_jenisBuku = $_POST['id_jenisBuku'];
    $id_kategoriBuku = $_POST['id_kategoriBuku'];
    $noPerolehan_buku = strtoupper(trim($_POST['noPerolehan_buku']));
    $noPanggil_buku = strtoupper(trim($_POST['noPanggil_buku']));
    $isbn = strtoupper(trim($_POST['isbn']));
    $lokasi_buku = strtoupper(trim($_POST['lokasi_buku']));
    $bahasa_buku = $_POST['bahasa_buku'];
    $kuantiti_buku = $_POST['kuantiti_buku'];
    
    // Handle file upload for gambar_buku
    $gambar_buku = '';
    if (isset($_FILES['gambar_buku']) && $_FILES['gambar_buku']['error'] == 0) {
        $fileTmpPath = $_FILES['gambar_buku']['tmp_name'];
        $fileName = $_FILES['gambar_buku']['name'];
        $fileSize = $_FILES['gambar_buku']['size'];
        $fileType = $_FILES['gambar_buku']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allow only specific file extensions
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Set the path to save the uploaded file
            $uploadFileDir = '../../images/books/';
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            // Move the file to the desired location
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar_buku = $newFileName;  // Save filename to be stored in the database
            } else {
                echo 'Error moving the file to the upload directory. Please make sure the directory is writable by the web server.';
                exit;
            }
        } else {
            echo 'Upload failed. Only jpg, jpeg, png, and gif files are allowed.';
            exit;
        }
    }

    // Insert data into the database
    $query = "INSERT INTO books 
        (judul_buku, pengarang_buku, penerbit_buku, tempat_terbit, tahun_terbit, mukasurat_buku, id_jenisBuku, id_kategoriBuku, noPerolehan_buku, noPanggil_buku, isbn, lokasi_buku, bahasa_buku, kuantiti_buku, gambar_buku) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = $connect->prepare($query);

    if (!$stmt) {
        die("Failed to prepare statement: " . $connect->error);
    }

    // Bind parameters dynamically based on the inputs
    $stmt->bind_param(
        "ssssiiissssssis", 
        $judul_buku, 
        $pengarang_buku, 
        $penerbit_buku, 
        $tempat_terbit, 
        $tahun_terbit, 
        $mukasurat_buku, 
        $id_jenisBuku, 
        $id_kategoriBuku, 
        $noPerolehan_buku, 
        $noPanggil_buku, 
        $isbn,
        $lokasi_buku, 
        $bahasa_buku, 
        $kuantiti_buku, 
        $gambar_buku
    );

    if ($stmt->execute()) {
        // Redirect with a success message
        header("Location: ../books.php?status=added");
    } else {
        // Redirect with an error message
        header("Location: ../books.php?status=addedError");
    }

    $stmt->close();
} else {
    // If not a POST request, redirect to the form page
    header("Location: ../book.php");
}

$connect->close();
?>
