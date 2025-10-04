<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_log("Portfolio backend triggered at " . date("Y-m-d H:i:s"));

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- CORS ---
header("Access-Control-Allow-Origin: https://arif-drab.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- Parse Input ---
$data = json_decode(file_get_contents("php://input"), true);
$name = htmlspecialchars(trim($data["name"] ?? ''));
$email = filter_var(trim($data["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($data["message"] ?? ''));

$mail = new PHPMailer(true);

try {
    // --- Main Mail ---
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = 'portfolioconnect7@gmail.com';
    $mail->Password = 'bvri vajt uzyp msam';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('portfolioconnect7@gmail.com', 'Md Arif Khan');
    $mail->addAddress('portfolioconnect7@gmail.com', 'Md Arif Khan');
    $mail->isHTML(true);
    $mail->Subject = "New Message from Portfolio!";
    $mail->Body = "Name: $name<br>Email: $email<br><br>Message:<br>$message";
    $mail->send();

    // --- Auto Reply ---
    $reply = new PHPMailer(true);
    $reply->isSMTP();
    $reply->Host = 'smtp.gmail.com';
    $reply->SMTPAuth = true;
    $reply->Username = 'portfolioconnect7@gmail.com';
    $reply->Password = 'bvri vajt uzyp msam';
    $reply->SMTPSecure = 'tls';
    $reply->Port = 587;

    $reply->setFrom('portfolioconnect7@gmail.com', 'Md Arif Khan');
    $reply->addAddress($email, $name);
    $reply->isHTML(true);
    $reply->Subject = "We've received your message!";
    $reply->Body = "Hi <b>$name</b>,<br><br>Thank you for reaching out. I'll respond soon.<br><br>— Md Arif Khan";

    $reply->send();

    echo json_encode([
        "status" => "success",
        "message" => "Email sent successfully!"
    ]);

} catch (Exception $e) {
    error_log("Mail Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Email failed: " . $mail->ErrorInfo
    ]);
}
?>
