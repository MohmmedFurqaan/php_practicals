<?php
    include "check_connection.php";

    // create the table in the DB if not exists

    $TABLE_QUERY = "create table if not exists Blogs(
        id int AUTO_INCREMENT PRIMARY KEY,
        title varchar(50),
        content text(350)
    )";

    if(mysqli_query($conn, $TABLE_QUERY)){
        echo "<br>Table created Successfully !";
    }
    else{
        echo "ERROR : ".mysqli_error($conn);

    }

    mysqli_close($conn);
?>