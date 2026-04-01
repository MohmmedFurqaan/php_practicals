<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes manually (since vendor/autoload.php doesn't exist)
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['send'])) {
    // Collect form data from index.php
    $recipient_email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output for diagnosing authentication error
        $mail->isSMTP();                                         // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                // Enable SMTP authentication
        $mail->Username   = 'furqaan.devspace2007@gmail.com';                  // SMTP username
        $mail->Password   = 'gxji zgzm ekcg tkly';                      // SMTP password (as requested)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable implicit TLS encryption
        $mail->Port       = 465;                                 // TCP port to connect to

        //Recipients
        $mail->setFrom('furqaan.devspace2007@gmail.com', 'Furqaan');
        $mail->addAddress($recipient_email);                    // Add a recipient from form data

        //Content
        $mail->isHTML(true);                                     // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);                   // Plain text version of message

        $mail->send();
        echo "<script>alert('Message has been sent'); window.location.href='index.php';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Redirect to index.php if accessed directly
    header("Location: index.php");
    exit();
}