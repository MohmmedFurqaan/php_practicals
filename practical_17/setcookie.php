<?php


if (isset($_POST["submit"])) {
$name = $_POST["username"];
$email = $_POST["email"];
$city = $_POST["city"];
//name
if (isset($_COOKIE["username"])) {

if ($_COOKIE["username"] == $name) {
echo "Cookie of name is already set with same name<br><br>";
} else {
setcookie("username", $name, time() + (60 * 60));
echo "Cookie of name is updated successfully<br><br>";
}
} else {
setcookie("username", $name, time() + 3600); echo "New Cookie of name is Created<br>";
}
//email
if (isset($_COOKIE["email"])) {
 
if ($_COOKIE["email"] == $email) {
echo "Cookie of email is already set with same name<br><br>";
} else {
setcookie("email", $email, time() + (60 * 60));
echo "Cookie of email is updated successfully<br>";
}
} else {
setcookie("email", $email, time() + 3600); echo "New Cookie of email is Created<br>";
}
//city
if (isset($_COOKIE["city"])) {

if ($_COOKIE["city"] == $city) {
echo "Cookie of city is already set with same name<br>";
} else {
setcookie("city", $city, time() + (60 * 60));
echo "Cookie of city is updated successfully<>";
}
} else {
setcookie("city", $city, time() + 3600); echo "New Cookie of city is Created";
}


}

echo "<br><br><a href='index.php'>Go Back</a>";
?>
