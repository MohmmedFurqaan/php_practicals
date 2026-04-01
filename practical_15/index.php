<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="send.php">
        <label>Enter Recipient Email ID : </label>
        <br>
        <input type="email" name="email">
        <br><br>
        <label>Enter Email Subject : </label>
        <input type="text" name="subject" id="">
        <br>
        <label>Enter Email Message : </label>
        <input type="text" name="message">
        <br><br>
        
        <br>
        <br><br>
        <input type="submit" value="SEND" name = "send">
</form>
</body>
</html>