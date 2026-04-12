<!DOCTYPE html>
<html>
<head>
<title>Online Order Form</title>
</head>
<body>

<h2>Online Order Form</h2>

<form action="send-email.php" method="POST">

<label>Email:</label><br>
<input type="email" name="email" required><br><br>

<label>Order Status:</label><br>
<select name="status" required>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select><br><br>

<input type="submit" value="Submit Order">

</form>

</body>
</html>
