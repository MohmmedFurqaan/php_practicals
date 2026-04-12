<?php include 'db.php'; ?>

<h2>Add Camper</h2>

<form method="POST">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    Sport: <input type="text" name="sport"><br><br>
    <input type="submit" name="add" value="Add">
</form>

<?php
if (isset($_POST['add'])) {
    $conn->query("INSERT INTO campers(name,email,sport)
    VALUES('{$_POST['name']}','{$_POST['email']}','{$_POST['sport']}')");
}
?>

<h2>Campers List</h2>

<table border="1">
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Sport</th><th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM campers");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>{$row['sport']}</td>
    <td>
    <a href='edit.php?id={$row['id']}'>Edit</a> |
    <a href='delete.php?id={$row['id']}'>Delete</a>
    </td>
    </tr>";
}
?>
</table>