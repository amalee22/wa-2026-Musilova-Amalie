<?php
class Category {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}