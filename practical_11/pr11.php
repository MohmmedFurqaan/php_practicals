
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>
<form method="post" action="pr 11_3.php">
<label for="name">name:</label>
<input type="text" name="name"><br><br>
<label for="email">email:<label>
<input type="email" name="email"><br><br>
<label for="contect">contect:</label>
<input type="number" name="contect"><br><br>

Gender:
<input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?>
value="male">Male
<input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?>
value="female">Female <br>
<p>Select your favorite Hobby:</p>
 
<input type="radio" id="Cricket" name="hobby" value="Cricket">
<label for="Cricket">Cricket</label><br>
<input type="radio" id="gaming" name="hobby" value="gaming">
<label for="gaming">Gaming</label><br>
<input type="radio" id="hockey" name="hobby" value="hockey">
<label for="hockey">Hockey</label><br>
<input type="submit" name="submit"value="submit"><br><br>
</from>

</body>
</html>
