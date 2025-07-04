<?php
include '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $reset_code = $_POST['reset_code'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (!empty($email) && !empty($reset_code) && !empty($new_password)) {
        // Check if the email and reset code match and are valid
        $sql = "SELECT reset_code_expiry FROM user WHERE email = ? AND reset_code = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param('ss', $email, $reset_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expiry_time = $row['reset_code_expiry'];

            // Check if the reset code has expired
            if (strtotime($expiry_time) > time()) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password in the database
                $sql_update = "UPDATE user SET password = ?, reset_code = NULL, reset_code_expiry = NULL WHERE email = ?";
                $stmt_update = $connect->prepare($sql_update);
                $stmt_update->bind_param('ss', $hashed_password, $email);

                if ($stmt_update->execute()) {
                    echo json_encode([
                        "success" => true,
                        "redirect" => "login-page.php?status=resetSuccess"
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Gagal mengemas kini kata laluan. Sila cuba lagi."
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Kod pengesahan telah tamat tempoh."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "E-mel atau kod pengesahan tidak sah."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Sila lengkapkan semua maklumat yang diperlukan."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Permintaan tidak sah."
    ]);
}

$connect->close();
?>
