<!DOCTYPE html>
<html>
<head>
<title>Application Submitted</title>
</head>
<body>

<h2>Submitted Details</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Collect data
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$qualification = htmlspecialchars($_POST['qualification']);
$experience = htmlspecialchars($_POST['experience']);

echo "<p><strong>Name:</strong> $name</p>"; echo "<p><strong>Email:</strong> $email</p>";
echo "<p><strong>Qualification:</strong> $qualification</p>"; echo "<p><strong>Experience:</strong> $experience years</p>";

} else {
echo "No data submitted.";
}
?>

<br>
<a href="index.php">Go Back</a>

</body>
</html>

