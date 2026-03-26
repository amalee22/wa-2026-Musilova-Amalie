<?php
require_once 'Database.php';

class Book {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO books (title, author, isbn, category, subcategory, year, price, link, description) 
                VALUES (:title, :author, :isbn, :category, :subcategory, :year, :price, :link, :description)";
        
        $stmt = $this->db->prepare($sql);

        // Propojení parametrů (ochrana proti SQL injection)
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':author', $data['author']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':subcategory', $data['subcategory']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':link', $data['link']);
        $stmt->bindParam(':description', $data['description']);

        return $stmt->execute();
    }

        // Metoda pro načtení všech knih
    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}