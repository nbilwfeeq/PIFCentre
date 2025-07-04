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

// Get the user ID from the session
$nokp = $_SESSION['user_id'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old-password'] ?? '';
    $newPassword = $_POST['new-password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Validate inputs
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        header("Location: ../update-password.php?status=error&message=All fields are required");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        header("Location: ../update-password.php?status=password_notMatch");
        exit();
    }

    // Get the current password from the database
    $stmt = $connect->prepare("SELECT password FROM user WHERE nokp = ?");
    if (!$stmt) {
        header("Location: ../update-password.php?status=error&message=Database error");
        exit();
    }
    $stmt->bind_param("s", $nokp);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the old password
    if (!password_verify($oldPassword, $hashedPassword)) {
        header("Location: ../update-password.php?status=password_notMatch");
        exit();
    }

    // Hash the new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $updateStmt = $connect->prepare("UPDATE user SET password = ? WHERE nokp = ?");
    if (!$updateStmt) {
        header("Location: ../update-password.php?status=error&message=Database error");
        exit();
    }
    $updateStmt->bind_param("ss", $newHashedPassword, $nokp);

    if ($updateStmt->execute()) {
        header("Location: ../update-password.php?status=password_updated");
    } else {
        header("Location: ../update-password.php?status=error&message=Failed to update password");
    }
    $updateStmt->close();
    exit();
} else {
    header("Location: ../update-password.php?status=error&message=Invalid request method");
    exit();
}
?>
