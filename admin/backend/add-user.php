<?php
session_start();

include '../plugins/functions.php';
include '../database/config.php';

if (isset($_POST['add'])) {
    $email = $_POST['email'];
    $nama_penuh = $_POST['nama_penuh'];
    $nokp = $_POST['nokp'];
    $notel = $_POST['notel'];
    $jawatan = $_POST['jawatan'];
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
    $program = isset($_POST['program']) ? $_POST['program'] : null;
    $matriks = isset($_POST['matriks']) ? $_POST['matriks'] : null;
    $password = $_POST['password'];
    $gambarUser = 'default-profile.png';

    // Validate required fields based on jawatan
    if ($jawatan === 'PELAJAR' && (!$tahun || !$program || !$matriks)) {
        header("Location: ../add.php?status=missingFields");
        exit();
    }

    // Prepare statements to prevent SQL injection
    $stmt_check = $connect->prepare("SELECT * FROM user WHERE nokp = ? OR email = ?");
    $stmt_check->bind_param("ss", $nokp, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // User already exists
        header("Location: ../create-user.php?status=exists");
        exit();
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare insert statement
        $stmt_insert = $connect->prepare("INSERT INTO user (email, nama_penuh, nokp, notel, tahun, program, matriks, password, jawatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Bind parameters
        $stmt_insert->bind_param("sssssssss", $email, $nama_penuh, $nokp, $notel, $tahun, $program, $matriks, $hashed_password, $jawatan);

        if ($stmt_insert->execute()) {
            // Successful registration
            header("Location: ../users.php?status=registered");
        } else {
            // Error during registration
            header("Location: ../create-user.php?status=registerError");
        }
    }
    $stmt_check->close();
    $stmt_insert->close();
    $connect->close();
} else {
    // If the form was not submitted properly
    header("Location: ../create-user.php?status=registerError");
}
?>
