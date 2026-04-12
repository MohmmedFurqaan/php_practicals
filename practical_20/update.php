<?php

    include "check_connection.php";

    // check if the form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // fetch the blog id
        $id = $_POST["id"];
        $title_option = $_POST["title_option"];
        $content_option = $_POST["content_option"];

        // Validate that at least one field is set to update
        if($title_option == "keep" && $content_option == "keep") {
            echo "Please select at least one field to update.<br>";
            echo "<a href='pages/update.html'>Back to Update</a>";
            exit;
        }

        // Check if blog exists
        $CHECK_QUERY = "SELECT id FROM blogs WHERE id = '$id'";
        $check_result = mysqli_query($conn, $CHECK_QUERY);

        if (mysqli_num_rows($check_result) == 0) {
            echo "Blog with ID " . htmlspecialchars($id) . " not found.<br>";
            echo "<a href='pages/update.html'>Back to Update</a>";
            exit;
        }

        // Build update query based on selections
        $SET_CLAUSES = array();

        if($title_option == "update") {
            $title = strtoupper($_POST["title"]);
            $SET_CLAUSES[] = "title = '$title'";
        }

        if($content_option == "update") {
            $content = strtolower($_POST["content"]);
            $SET_CLAUSES[] = "content = '$content'";
        }

        // Create UPDATE query
        $SET_STRING = implode(", ", $SET_CLAUSES);
        $UPDATE_QUERY = "UPDATE blogs SET $SET_STRING WHERE id = '$id'";

        if (mysqli_query($conn, $UPDATE_QUERY)) {
            echo "Blog updated successfully!<br>";
            echo "<a href='pages/update.html'>Back to Update</a>";
            exit; 
        } else {
            echo "Error updating blog: " . mysqli_error($conn) . "<br>";
            echo "<a href='pages/update.html'>Back to Update</a>";
        }

    }

?>
