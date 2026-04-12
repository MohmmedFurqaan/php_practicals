<!DOCTYPE html>
<html>
<head>
<title>Job Application Form</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Job Application Form</h2>

<form action="submit.php" method="POST">

<label>Name:</label><br>
<input type="text" name="name" required><br><br>

<label>Email:</label><br>
<input type="email" name="email" required><br><br>

<label>Qualification:</label><br>
<input type="text" name="qualification" required><br><br>

<label>Experience (in years):</label><br>
<input type="number" name="experience" required><br><br>
<input type="submit" value="Submit">

</form>
</body>
</html>
