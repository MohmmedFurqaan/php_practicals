<!DOCTYPE html>
<html>
<head>
    <title>Contact Manager</title>
</head>
<body>

<?php
if (isset($_SESSION['edit_id'])) {
    $contact = $controller->getById($_SESSION['edit_id']);
?>
<h2>Edit Contact</h2>
<form method="POST">
    <input type="text" name="name" value="<?php echo htmlspecialchars($contact['name']); ?>" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
    <button name="save_update">Save Update</button>
    <button name="cancel_update" type="button" onclick="location.reload();">Cancel</button>
</form>
<?php
} else {
?>
<h2>Add Contact</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <button name="add">Add</button>
</form>
<?php
}
?>

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
            <button name='update'>Update</button>
        </form>
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