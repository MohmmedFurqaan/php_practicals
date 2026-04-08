<?php
session_start();
require_once "database_Config.php";
require_once "Contact_Controller.php";

$db = (new Database())->connect();
$controller = new ContactController($db);

// ADD
if (isset($_POST['add'])) {
    $controller->add($_POST);
}

// UPDATE
if (isset($_POST['update'])) {
    $_SESSION['edit_id'] = $_POST['id'];
}

// SAVE UPDATE
if (isset($_POST['save_update']) && isset($_SESSION['edit_id'])) {
    $data = array(
        'id' => $_SESSION['edit_id'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone']
    );
    $controller->update($data);
    unset($_SESSION['edit_id']);
}

// CANCEL UPDATE
if (isset($_POST['cancel_update'])) {
    unset($_SESSION['edit_id']);
}

// DELETE
if (isset($_POST['delete'])) {
    $controller->delete($_POST['id']);
}

// SEARCH or READ
if (isset($_POST['search'])) {
    $contacts = $controller->search($_POST['keyword']);
} else {
    $contacts = $controller->getAll();
}

include "contact_view.php";
?>