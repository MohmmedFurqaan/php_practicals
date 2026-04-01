<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>
<?php
//while loop
$f = 1;
$i = 5;
echo "<h1>Factorial using while loop:</h1>"; while ($i > 0) {
$f = $f * $i;
$i--;
}
echo "Factorial is $f";

?>
</body>
</html>
