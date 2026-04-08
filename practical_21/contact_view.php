<!DOCTYPE html>
<html>
<head>
    <title>Contact Manager</title>
</head>
<body>

<h2>Add Contact</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <button name="add">Add</button>
</form>

<h2>Search</h2>
<form method="POST">
    <input type="text" name="keyword">
    <button name="search">Search</button>
</form>

<h2>Contact List</h2>

<?php
if (isset($contacts)) {
    while ($row = $contacts->fetch_assoc()) {
        echo $row['name']." | ".$row['email']." | ".$row['phone'];

        echo "
        <form method='POST' style='display:inline'>
            <input type='hidden' name='id' value='".$row['id']."'>
            <button name='delete'>Delete</button>
        </form>
        <br>";
    }
}
?>

</body>
</html>