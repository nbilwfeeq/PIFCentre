<?php
include '../database/config.php';
include '../session/security.php';

if (isset($_POST['update'])) {
    $id_buku = $_POST['id_buku'];
    $judul_buku = strtoupper($_POST['judul_buku']);
    $pengarang_buku = strtoupper($_POST['pengarang_buku']);
    $penerbit_buku = strtoupper($_POST['penerbit_buku']);
    $tempat_terbit = strtoupper($_POST['tempat_terbit']);
    $tahun_terbit = $_POST['tahun_terbit'];
    $mukasurat_buku = $_POST['mukasurat_buku'];
    $isbn = $_POST['isbn'];
    $id_jenisBuku = $_POST['id_jenisBuku'];
    $id_kategoriBuku = $_POST['id_kategoriBuku'];
    $noPerolehan_buku = strtoupper($_POST['noPerolehan_buku']);
    $noPanggil_buku = strtoupper($_POST['noPanggil_buku']);
    $lokasi_buku = strtoupper($_POST['lokasi_buku']);
    $bahasa_buku = $_POST['bahasa_buku'];
    $gambar_buku = $_FILES['gambar_buku'];

    // Handle file upload if a new image is uploaded
    $gambar_buku_name = null;
    if (!empty($gambar_buku['name'])) {
        $gambar_buku_name = basename($gambar_buku['name']);
        $target_dir = "../../images/books/";
        $target_file = $target_dir . $gambar_buku_name;

        if (!move_uploaded_file($gambar_buku['tmp_name'], $target_file)) {
            header("Location: ../update-book.php?id=$id_buku&status=uploadError");
            exit;
        }
    }

    // SQL query to update the book details
    if ($gambar_buku_name) {
        // With a new image
        $updateQuery = "UPDATE books 
                        SET judul_buku = ?, pengarang_buku = ?, penerbit_buku = ?, tempat_terbit = ?, tahun_terbit = ?, 
                            mukasurat_buku = ?, isbn = ?, id_jenisBuku = ?, id_kategoriBuku = ?, noPerolehan_buku = ?, 
                            noPanggil_buku = ?, lokasi_buku = ?, bahasa_buku = ?, gambar_buku = ?
                        WHERE id_buku = ?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ssssiiisiissssi", $judul_buku, $pengarang_buku, $penerbit_buku, $tempat_terbit, $tahun_terbit, 
                          $mukasurat_buku, $isbn, $id_jenisBuku, $id_kategoriBuku, $noPerolehan_buku, $noPanggil_buku, 
                          $lokasi_buku, $bahasa_buku, $gambar_buku_name, $id_buku);
    } else {
        // Without a new image
        $updateQuery = "UPDATE books 
                        SET judul_buku = ?, pengarang_buku = ?, penerbit_buku = ?, tempat_terbit = ?, tahun_terbit = ?, 
                            mukasurat_buku = ?, isbn = ?, id_jenisBuku = ?, id_kategoriBuku = ?, noPerolehan_buku = ?, 
                            noPanggil_buku = ?, lokasi_buku = ?, bahasa_buku = ?
                        WHERE id_buku = ?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ssssiiisiisssi", $judul_buku, $pengarang_buku, $penerbit_buku, $tempat_terbit, $tahun_terbit, 
                          $mukasurat_buku, $isbn, $id_jenisBuku, $id_kategoriBuku, $noPerolehan_buku, $noPanggil_buku, 
                          $lokasi_buku, $bahasa_buku, $id_buku);
    }

    // Execute the query and handle errors
    if ($stmt->execute()) {
        header("Location: ../books.php?status=updated");
    } else {
        header("Location: ../update-book.php?id=$id_buku&status=updateError");
    }

    $stmt->close();
    $connect->close();
} else {
    header("Location: ../books.php");
    exit;
}
?>
