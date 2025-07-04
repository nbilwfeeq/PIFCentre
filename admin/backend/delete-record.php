<?php
session_start();

ini_set('display_errors', 0); // Turn off error display
error_reporting(E_ALL & ~E_WARNING);

include '../database/config.php'; // Pastikan jalan fail betul
include '../session/security.php'; // Pastikan jalan fail betul

// Periksa jika permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRecord = $_POST['id_record'] ?? null;

    if ($idRecord) {
        // Sediakan penyataan untuk memadam rekod
        $stmt = $connect->prepare("DELETE FROM records WHERE id_record = ?");
        $stmt->bind_param("i", $idRecord);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Rekod berjaya dipadam.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memadam rekod.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID Rekod tidak sah.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Kaedah permintaan tidak dibenarkan.']);
}

$connect->close();
