<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "blog_db";

    // Step 1: connect WITHOUT database
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Step 2: create database
    $sql = "CREATE DATABASE IF NOT EXISTS $db";

    if (!mysqli_query($conn, $sql)) {
        die("Error creating database: " . mysqli_error($conn));
    }

    // Step 3: connect to the database
    $conn = mysqli_connect($servername, $username, $password, $db);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } 

    // Close connection
    // mysqli_close($conn);
?>