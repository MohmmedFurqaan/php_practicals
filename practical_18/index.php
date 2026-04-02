<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exception Handling</title>
</head>

<body>

    <h2>Exception Handling</h2>

    <form method="POST">
        Enter Age :-
        <input type="number" name="age">
        <br><br>
        <input type="submit" name="submit" value="Check">
        <br><br>
    </form>

</body>

</html>
<?php
    if (isset($_POST['age'])) {
        $age = $_POST['age'];
        try {
            if ($age < 18) {

                throw new Exception("You are not eligible");
            } else {
                echo "You are eligible";
            }
        } catch (Exception $e) {
            echo "<b>Error :</b> " . $e->getMessage();
            echo "<br><br>";
        } finally {
            echo "<br>Thank you for using this Application";
        }
    }

?>