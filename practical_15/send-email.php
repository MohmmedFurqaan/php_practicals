<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes manually (since vendor/autoload.php doesn't exist)
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
// Server settings
// $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Uncomment this line to see detailed error logs
$mail->isSMTP();
$mail->Host	= 'smtp.gmail.com';
$mail->SMTPAuth	= true;
$mail->Username	= 'furqaan.devspace2007@gmail.com';
$mail->Password	= 'gxji zgzm ekcg tkly'; // Ensure this is a valid App Password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port	= 587;

// Check if POST data exists to avoid "Undefined Index" errors
$name = isset($_POST['name1']) ? $_POST['name1'] : 'Subscriber';
$email = isset($_POST['email1']) ? $_POST['email1'] : '';

if (empty($email)) {
throw new Exception("Email address is required.");
}

// Recipients
$mail->setFrom('furqaan.devspace2007@gmail.com', 'Admin');
$mail->addAddress($email, $name);

// Content
$mail->isHTML(true);
 
$mail->Subject = "Subscription Confirmed";
$mail->Body	= "
<h2 style='color:green'>Welcome " . htmlspecialchars($name) . "!</h2>
<p>Thank you for subscribing to our newsletter.</p>
<hr>
<b>Regards,</b><br>Admin";

$mail->AltBody = "Hello $name, Thank you for subscribing.";

$mail->send();
echo "HTML Email Sent Successfully";
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
