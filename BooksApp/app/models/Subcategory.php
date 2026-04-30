<?php
class Subcategory {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAllSubcategories() {
        $stmt = $this->db->prepare("SELECT * FROM subcategories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}