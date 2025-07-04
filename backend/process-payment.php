<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recordId = $_POST['record_id'];
    $paymentType = $_POST['payment_type'];
    $paymentProof = isset($_FILES['payment_proof']) ? $_FILES['payment_proof'] : null;

    // Check for the payment type
    if ($paymentType == 'TNG') {
        // If payment type is TNG, validate the payment proof
        if ($paymentProof) {
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!in_array($paymentProof['type'], $allowedTypes)) {
                die("Format fail tidak dibenarkan. Sila muat naik fail PNG, JPG, atau JPEG sahaja.");
            }

            // Define upload directory
            $uploadDir = '../images/payment_proof/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate a unique file name
            $fileExtension = pathinfo($paymentProof['name'], PATHINFO_EXTENSION);
            $randomFileName = uniqid('payment_', true) . '.' . $fileExtension;

            // Full file path
            $filePath = $uploadDir . $randomFileName;

            // Move the uploaded file to the specified directory
            if (!move_uploaded_file($paymentProof['tmp_name'], $filePath)) {
                die("Gagal memuat naik fail.");
            }

            $fileName = $randomFileName;
        } else {
            // If payment proof is missing for TNG, return an error
            die("Bukti pembayaran diperlukan untuk jenis pembayaran TNG.");
        }

        // Update record with status_payment = 1 for TNG
        $statusPayment = 1;
    } else {
        // If payment type is CASH, no payment proof is required
        $fileName = null;
        
        // Update record with status_payment = 2 for CASH
        $statusPayment = 2;
    }

    // Update record in the database
    $stmt = $connect->prepare("UPDATE records SET payment_type = ?, payment_proof = ?, status_payment = ? WHERE id_record = ?");
    $stmt->bind_param("ssii", $paymentType, $fileName, $statusPayment, $recordId);

    if ($stmt->execute()) {
        // Redirect to the records page with a success status
        header("Location: ../records.php?status=success");
        exit();
    } else {
        echo "Ralat semasa merekod pembayaran.";
    }
}
?>
