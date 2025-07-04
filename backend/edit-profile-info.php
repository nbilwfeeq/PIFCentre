<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';
include '../session/security.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-page.php?status=unauthorized");
    exit();
}

$nokp = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_info') {
    // Get form data
    $nama_penuh = htmlspecialchars($_POST['nama_penuh']);
    $email = htmlspecialchars($_POST['email']);
    $notel = htmlspecialchars($_POST['notel']);
    $jawatan = htmlspecialchars($_POST['jawatan']);
    $tahun = $jawatan === 'PELAJAR' ? htmlspecialchars($_POST['tahun']) : NULL;
    $matriks = $jawatan === 'PELAJAR' ? htmlspecialchars($_POST['matriks']) : NULL;
    $program = $jawatan === 'PELAJAR' ? htmlspecialchars($_POST['program']) : NULL;

    // Handle profile picture upload
    if (isset($_FILES['gambarUser']) && $_FILES['gambarUser']['error'] === UPLOAD_ERR_OK) {
        $targetDir = '../images/profile-pic/';
        $fileName = basename($_FILES['gambarUser']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedTypes)) {
            // Create unique filename
            $uniqueFileName = $nokp . '_' . time() . '.' . $fileExtension;
            $targetFilePath = $targetDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['gambarUser']['tmp_name'], $targetFilePath)) {
                $gambarUser = $uniqueFileName;

                // Update the profile picture in the database
                $stmt = $connect->prepare("UPDATE user SET gambarUser = ? WHERE nokp = ?");
                $stmt->bind_param("ss", $gambarUser, $nokp);
                $stmt->execute();
            } else {
                header("Location: ../update-profile.php?status=file_upload_error");
                exit();
            }
        } else {
            header("Location: ../update-profile.php?status=invalid_file_type");
            exit();
        }
    }

    // Update user info in the database
    $stmt = $connect->prepare("UPDATE user SET nama_penuh = ?, email = ?, notel = ?, jawatan = ?, tahun = ?, matriks = ?, program = ? WHERE nokp = ?");
    $stmt->bind_param("ssssssss", $nama_penuh, $email, $notel, $jawatan, $tahun, $matriks, $program, $nokp);

    if ($stmt->execute()) {
        header("Location: ../update-profile.php?status=success");
        exit();
    } else {
        header("Location: ../update-profile.php?status=db_error");
        exit();
    }
} else {
    header("Location: ../update-profile.php?status=invalid_request");
    exit();
}
