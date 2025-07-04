<?php

session_start();

include '../plugins/functions.php';
include '../database/config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $connect->prepare("SELECT * FROM user_admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Successful login, store nokp in session and redirect to dashboard
        $_SESSION['user_id'] = $username;
        header("Location: ../dashboard.php?status=loggedIn");
    } else {
        // Unsuccessful login, redirect back to login with status
        header("Location: ../login-page.php?status=invalid");
    }
} else {
    // If the form was not submitted properly
    header("Location: ../login-page.php?status=error");
}
?>
