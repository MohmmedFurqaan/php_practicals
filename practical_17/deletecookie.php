<?php
if (isset($_COOKIE["username"])) {
    setcookie("username", "", time() - 3600);
    setcookie("email", "", time() - 3600);
    setcookie("city", "", time() - 3600); 
    echo "Cookie Deleted Successfully";
} else {
    echo "No Cookie available to delete";
 
    }
    echo "<br><br><a href='index.php'>Go Back to home</a>";
?>
