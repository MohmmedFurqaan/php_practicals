<?php
    include "check_connection.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        // fetch the blog etails from the user form

        $id = $_POST['id'];

        // delete query
        $DELETE_QUERY = "DELETE FROM blogs WHERE id = '$id'";

        if (mysqli_query($conn, $DELETE_QUERY)) {
            echo "Blog deleted successfully!";
            echo "<br>";
            echo "<a href = 'view_blogs.php'>view blogs</a>";
            exit;
        } else {
            echo "Error deleting blog: " . mysqli_error($conn);
        }

    }

?>