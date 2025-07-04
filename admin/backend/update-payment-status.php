<?php
include '../database/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_record = $_POST['id_record'] ?? null;

    if ($id_record) {
        $stmt = $connect->prepare("UPDATE records SET status_payment = 1 WHERE id_record = ?");
        $stmt->bind_param('i', $id_record);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengemaskini status.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID rekod tidak disediakan.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak sah.']);
}

$connect->close();
?>
