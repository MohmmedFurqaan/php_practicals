<?php
require_once "Contact_model.php";

class ContactController {
    private $model;

    public function __construct($db) {
        $this->model = new Contact($db);
    }

    public function add($data) {
        return $this->model->create($data['name'], $data['email'], $data['phone']);
    }

    public function getAll() {
        return $this->model->read();
    }

    public function search($keyword) {
        return $this->model->search($keyword);
    }

    public function update($data) {
        return $this->model->update($data['id'], $data['name'], $data['email'], $data['phone']);
    }

    public function delete($id) {
        return $this->model->delete($id);
    }
}
?>