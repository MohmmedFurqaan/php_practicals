<?php
class Contact {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ADD
    public function create($name, $email, $phone) {
        $stmt = $this->conn->prepare("INSERT INTO contacts(name,email,phone) VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $phone);
        return $stmt->execute();
    }

    // READ (All)
    public function read() {
        return $this->conn->query("SELECT * FROM contacts");
    }

    // READ (Single)
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // SEARCH
    public function search($keyword) {
        $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE name LIKE ?");
        $search = "%".$keyword."%";
        $stmt->bind_param("s", $search);
        $stmt->execute();
        return $stmt->get_result();
    }

    // UPDATE
    public function update($id, $name, $email, $phone) {
        $stmt = $this->conn->prepare("UPDATE contacts SET name=?, email=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>