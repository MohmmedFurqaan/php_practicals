<?php include 'db.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM campers WHERE id=$id")->fetch_assoc();
?>

<form method="POST">
Name: <input type="text" name="name" value="<?= $data['name'] ?>"><br><br>
Email: <input type="email" name="email" value="<?= $data['email'] ?>"><br><br>
Sport: <input type="text" name="sport" value="<?= $data['sport'] ?>"><br><br>
<input type="submit" name="update" value="Update">
</form>

<?php
if (isset($_POST['update'])) {
    $conn->query("UPDATE campers SET
    name='{$_POST['name']}',
    email='{$_POST['email']}',
    sport='{$_POST['sport']}'
    WHERE id=$id");

    header("Location: index.php");
}
?>