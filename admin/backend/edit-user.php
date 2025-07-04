<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';

if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $nama_penuh = $_POST['nama_penuh'];
    $nokp = $_POST['nokp'];
    $notel = $_POST['notel'];
    $jawatan = $_POST['jawatan'];
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
    $program = isset($_POST['program']) ? $_POST['program'] : null;
    $matriks = isset($_POST['matriks']) ? $_POST['matriks'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $gambarUser = 'default-profile.png';
    
    // Prepare the base update statement
    $sql = "UPDATE user SET email = ?, nama_penuh = ?, jawatan = ?, program = ?, matriks = ?, tahun = ?, notel = ?";

    // Add password update if a new password is provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password = ?";
    }
    
    $sql .= " WHERE nokp = ?";

    // Prepare the statement
    $stmt = $connect->prepare($sql);

    if (!empty($password)) {
        $stmt->bind_param("sssssssss", $email, $nama_penuh, $jawatan, $program, $matriks, $tahun, $notel, $hashed_password, $nokp);
    } else {
        $stmt->bind_param("ssssssss", $email, $nama_penuh, $jawatan, $program, $matriks, $tahun, $notel, $nokp);
    }

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../users.php?status=edited");
    } else {
        header("Location: ../users.php?status=failed");
    }

    // Close the statement and connection
    $stmt->close();
    $connect->close();
} else {
    header("Location: ../users.php?status=missingData");
}
?>
