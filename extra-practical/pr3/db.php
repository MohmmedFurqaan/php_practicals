<?php
$conn = new mysqli("localhost", "root", "", "sports_club");

if ($conn->connect_error) {
    die("Connection Failed");
}
?>