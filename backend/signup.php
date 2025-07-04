<?php
session_start();

include '../plugins/functions.php';
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $nama_penuh = $_POST['nama_penuh'];
    $nokp = $_POST['nokp'];
    $jawatan = $_POST['jawatan'];
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
    $program = isset($_POST['program']) ? $_POST['program'] : null;
    $matriks = isset($_POST['matriks']) ? $_POST['matriks'] : null;
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $gambarUser = 'default-profile.png';

    // Validate required fields
    if ($password !== $confirm_password) {
        header("Location: ../register.php?status=passwordMismatch");
        exit();
    }

    if ($jawatan === 'PELAJAR' && (!$tahun || !$program || !$matriks)) {
        header("Location: ../register.php?status=missingFields");
        exit();
    }

    // Handle file upload (if applicable)
    if (isset($_FILES['gambarUser']) && $_FILES['gambarUser']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambarUser']['tmp_name'];
        $fileName = $_FILES['gambarUser']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Check if the file has one of the allowed extensions
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../images/profile-pic/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambarUser = $newFileName;
            } else {
                header("Location: ../register.php?status=fileUploadError");
                exit();
            }
        } else {
            header("Location: ../register.php?status=invalidFileType");
            exit();
        }
    }

    // Check for duplicate entries
    $stmt_check = $connect->prepare("SELECT * FROM user WHERE nokp = ? OR email = ?");
    $stmt_check->bind_param("ss", $nokp, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: ../register.php?status=exists");
        exit();
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into the database
        $stmt_insert = $connect->prepare(
            "INSERT INTO user (email, nama_penuh, nokp, tahun, program, matriks, password, jawatan, gambarUser) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt_insert->bind_param(
            "sssssssss", 
            $email, $nama_penuh, $nokp, $tahun, $program, $matriks, $hashed_password, $jawatan, $gambarUser
        );

        if ($stmt_insert->execute()) {
            header("Location: ../login-page.php?status=registered");
        } else {
            header("Location: ../register.php?status=registerError");
        }
    }

    // Close database connections
    $stmt_check->close();
    $stmt_insert->close();
    $connect->close();
} else {
    header("Location: ../register.php?status=invalidRequest");
}
?>
