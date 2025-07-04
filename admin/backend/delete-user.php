<?php 

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../session/security.php';
include '../database/config.php';
include '../plugins/functions.php';

if (isset($_GET['nokp'])) {
    $nokp = $_GET['nokp'];

    $stmt = $connect->prepare("DELETE FROM user WHERE nokp = ?");
    $stmt->bind_param("s", $nokp);

    if ($stmt->execute()) {
        header("Location: ../users.php?status=deleted");
    } else {
        header("Location: ../users.php?status=failed");
    }

    $stmt->close();
} else {
    header("Location: ../users.php?status=critical");
}

$connect->close();

?>