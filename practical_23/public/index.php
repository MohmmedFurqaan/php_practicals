<?php
require_once "../config/db.php";
require_once "../models/Product.php";

$product = new Product($conn);
$data = $product->getAll();

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $product->getById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management</title>
</head>
<body>

<h2><?php echo $editData ? "Edit Product" : "Add Product"; ?></h2>

<form method="POST" action="../controller/ProductController.php">
    <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

    Name: <input type="text" name="name" value="<?php echo $editData['name'] ?? ''; ?>" required><br><br>
    Category: <input type="text" name="category" value="<?php echo $editData['category'] ?? ''; ?>"><br><br>
    Quantity: <input type="number" name="quantity" value="<?php echo $editData['quantity'] ?? 0; ?>" required><br><br>
    Price: <input type="number" step="0.01" name="price" value="<?php echo $editData['price'] ?? ''; ?>" required><br><br>

    <?php if ($editData) { ?>
        <button type="submit" name="update">Update</button>
    <?php } else { ?>
        <button type="submit" name="add">Add</button>
    <?php } ?>
</form>

<hr>

<h2>Inventory</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Category</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Actions</th>
</tr>

<?php while($row = $data->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['category']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td><?php echo $row['price']; ?></td>
    <td>
        <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
        <a href="../controller/ProductController.php?delete=<?php echo $row['id']; ?>">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>