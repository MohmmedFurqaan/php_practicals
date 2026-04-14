<?php
require_once "../config/db.php";
require_once "../models/Product.php";

$product = new Product($conn);

// ADD
if (isset($_POST['add'])) {
    $product->add(
        $_POST['name'],
        $_POST['category'],
        $_POST['quantity'],
        $_POST['price']
    );
    header("Location: ../public/index.php");
}

// DELETE
if (isset($_GET['delete'])) {
    $product->delete($_GET['delete']);
    header("Location: ../public/index.php");
}

// UPDATE
if (isset($_POST['update'])) {
    $product->update(
        $_POST['id'],
        $_POST['name'],
        $_POST['category'],
        $_POST['quantity'],
        $_POST['price']
    );
    header("Location: ../public/index.php");
}
?>