<?php
require_once "database_Config.php";
require_once "Contact_Controller.php";

$db = (new Database())->connect();
$controller = new ContactController($db);

// ADD
if (isset($_POST['add'])) {
    $controller->add($_POST);
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