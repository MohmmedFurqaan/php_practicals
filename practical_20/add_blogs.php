<?php

    include "check_connection.php";

    // check if the button submit is pressed

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // fetch the blogs title and content
        $id = $_POST["id"];
        $title = strtoupper($_POST["title"]);
        $content = strtolower($_POST["content"]);
        
        // inset query
        $INSERT_QUERY = "INSERT INTO blogs
        (id, title, content) VALUES ('$id', '$title', '$content')";

        if (mysqli_query($conn, $INSERT_QUERY)) {
            echo "Blog added successfully!";
            echo "<script>window.location.href = '/practical_20/view_blogs.php';</script>";
            exit; 
        } else {
            echo "Error adding blog: " . mysqli_error($conn);
        }

    }
    

?>