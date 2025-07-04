<?php

session_start();

include '../plugins/functions.php';
include '../database/config.php';
include '../session/security.php';

// Function to verify password with hashing
function verifyPassword($inputPassword, $storedHash) {
    return password_verify($inputPassword, $storedHash);
}

if (isset($_POST['login'])) {
    // Sanitize and validate input
    $nokp = filter_var($_POST['nokp'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $connect->prepare("SELECT * FROM user WHERE nokp = ?");
    $stmt->bind_param("s", $nokp);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $storedHash = $user['password']; // Assuming password is stored as a hash

        // Verify password
        if (verifyPassword($password, $storedHash)) {
            // Successful login, store user_id in session and redirect to dashboard
            $_SESSION['user_id'] = $nokp;
            header("Location: ../loading-page.php?target=dashboard.php?status=loggedIn");
        } else {
            // Incorrect password
            header("Location: ../login-page.php?status=invalid");
        }
    } else {
        // User not found
        header("Location: ../login-page.php?status=invalid");
    }
    exit();
} else {
    // Form not submitted properly
    header("Location: ../login-page.php?status=error");
    exit();
}

?>
