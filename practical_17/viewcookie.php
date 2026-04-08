<?php

if (count($_COOKIE) > 0) {
echo "<h3>Stored Cookies:</h3>";

foreach ($_COOKIE as $key => $value) { echo "<b>$key</b>: $value<br>";
}
} else {

echo "No cookie found";
}

echo "<br><br><a href='index.php'>Go Back</a>";
?>
