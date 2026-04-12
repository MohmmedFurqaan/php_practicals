<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);
try {
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'mmftdec@gmail.com';
	$mail->Password = "APP PASSWORD"; // Use environment variable for security
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port	= 587;

$email = isset($_POST['email']) ? $_POST['email'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (empty($email)) {
throw new Exception("Email is required.");
}

$mail->setFrom('mmftdec@gmail.com', 'Order Genreatr Bot');
$mail->addAddress($email);

$mail->isHTML(true);
$mail->Subject = "Order Confirmation";

$mail->Body = "
<html>
<body>
<h2 style='color:blue;'>Order Confirmation</h2>
<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
<p><strong>Order Status:</strong> " . htmlspecialchars($status) . "</p>
 
<p>Thank you for your order.</p>
<hr>
<p>Regards,<br>Online Order System</p>
</body>
</html>
";

$mail->AltBody = "Email: $email | Order Status: $status";

$mail->send();
echo "<h3>Email Sent Successfully to $email</h3>";

} catch (Exception $e) {
echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
?>

