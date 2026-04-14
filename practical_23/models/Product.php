<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM products ORDER BY created_at DESC");
    }

    public function add($name, $category, $quantity, $price) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products (name, category, quantity, price) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssid", $name, $category, $quantity, $price);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function update($id, $name, $category, $quantity, $price) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET name=?, category=?, quantity=?, price=? WHERE id=?"
        );
        $stmt->bind_param("ssidi", $name, $category, $quantity, $price, $id);
        return $stmt->execute();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>