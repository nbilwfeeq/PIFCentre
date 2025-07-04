<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../database/config.php';
include '../session/security.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-page.php?status=unauthorized");
    exit();
}

$nokp = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $uploadDir = '../images/profile-pic/';
    $allowedTypes = ['jpeg', 'png', 'jpg', 'gif'];

    $fileName = $_FILES['profileImage']['name'];
    $fileTmp = $_FILES['profileImage']['tmp_name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = uniqid($nokp . '_') . '.' . $fileType;
    $uploadPath = $uploadDir . $newFileName;

    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        header("Location: ../update-profile.php?status=invalidFileType");
        exit();
    }

    // Validate upload success
    if (!is_uploaded_file($fileTmp)) {
        header("Location: ../update-profile.php?status=uploadError");
        exit();
    }

    // Attempt to move the uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        // Update the database with the new image name
        $sql = "UPDATE user SET gambarUser = ? WHERE nokp = ?";
        $stmt = $connect->prepare($sql);

        if (!$stmt) {
            header("Location: ../update-profile.php?status=dbPreparationFailed");
            exit();
        }

        $stmt->bind_param('ss', $newFileName, $nokp);

        if ($stmt->execute()) {
            header("Location: ../update-profile.php?status=profileUpdated");
            exit();
        } else {
            header("Location: ../update-profile.php?status=dbUpdateFailed");
            exit();
        }
    } else {
        header("Location: ../update-profile.php?status=fileSaveFailed");
        exit();
    }
} else {
    header("Location: ../update-profile.php?status=invalidRequest");
    exit();
}
?>
