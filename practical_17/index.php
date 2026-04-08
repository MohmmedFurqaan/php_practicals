<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | cookie</title>
</head>
<body>
    <?php
        if (isset($_COOKIE["username"])){
            echo "Welcome ! ".$_COOKIE["username"];
        }
        
        echo "<h2>Fill the form</h2>";
?>
            <form method="post" action="setcookie.php"> Enter Name:
                <input type="text" name="username" required><br><br> Enter Email:
                <input type="email" name="email" required><br><br> Enter City:
                <input type="text" name="city" required><br><br>
                <input type="submit" name="submit" value="save cookie">
            </form>

            <br>
            <a href="viewcookie.php">View Cookie</a>
            <br><br>
            <a href="deletecookie.php">Delete Cookie</a>
            
        
</body>
</html>