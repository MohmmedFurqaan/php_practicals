<?php if(isset($_POST["submit"])){
$name = $_POST["name"];
$email = $_POST["email"];
$contect = $_POST["contect"];
$gender = $_POST["gender"];
$hobby = $_POST["hobby"];

if (isset($_POST["gender"])) {
$genderErr = "Gender is required";
} else {
$gender = $_POST["gender"];
}
echo "Name : $name <br>"; echo "Email : $email <br>"; echo "Contenct : $contect <br>"; echo "Gender : $gender <br>" ; echo "Favorite Hobby : $hobby";
}
?>
