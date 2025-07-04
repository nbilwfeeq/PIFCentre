<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../database/config.php';

// Check if data is received as JSON
if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['email'])) {
        $email = $data['email'];

        // Check if email exists in the database
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $code = bin2hex(random_bytes(4)); // Generate 8-character code

            $sql_update = "UPDATE user SET reset_code = ?, reset_code_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?";
            $stmt_update = $connect->prepare($sql_update);
            $stmt_update->bind_param('ss', $code, $email);

            if ($stmt_update->execute()) {
                $mail = new PHPMailer(true);
            
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ahmadnabilwafiq@gmail.com';
                    $mail->Password = 'xbnz uyng htxk xctm'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
            
                    $mail->setFrom('pifcenterkvks@gmail.com', 'Perpustakaan Ibnu Firnas Knowledge Centre');
                    $mail->addAddress($email);
            
                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Kata Laluan Anda';
                    $mail->Body    = "<p>Kod reset kata laluan anda ialah: <strong>$code</strong></p><p>Kod ini sah selama 15 minit.</p>";
                    $mail->AltBody = "Kod reset kata laluan anda ialah: $code Kod ini sah selama 15 minit.";
            
                    $mail->send();
                    // Redirect to form-reset-password.php with email as a query parameter
                    echo json_encode(["success" => true, "redirect" => "form-reset-password.php?email=" . urlencode($email)]);
                } catch (Exception $e) {
                    echo json_encode(["success" => false, "message" => "Gagal menghantar e-mel. Error: {$mail->ErrorInfo}"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Gagal mengemas kini kod reset. Sila cuba lagi."]);
            }            
        } else {
            echo json_encode(["success" => false, "message" => "E-mel tidak dijumpai dalam sistem."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Sila masukkan e-mel yang sah."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request. Make sure to send JSON data with the correct headers."]);
}

$connect->close();
?>
