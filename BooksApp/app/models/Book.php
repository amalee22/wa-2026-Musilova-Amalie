<?php

class Book {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(string $title, string $author, string $category, string $subcategory, int $year, float $price, string $isbn, string $description, string $link, array $images, int $userId): bool {
        $sql = "INSERT INTO books (title, author, category, subcategory, year, price, isbn, description, link, images, created_by) 
                VALUES (:title, :author, :category, :subcategory, :year, :price, :isbn, :description, :link, :images, :created_by)";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':isbn' => $isbn,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':created_by' => $userId
        ]);
    }

    // 💡 ZMĚNA: Upravená metoda getAll() využívající LEFT JOIN pro zjištění názvu kategorie
    public function getAll(): array {
        $sql = "SELECT books.*, categories.name AS category_name 
                FROM books 
                LEFT JOIN categories ON books.category = categories.id 
                ORDER BY books.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function getById(int $id): ?array {
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        return $book ?: null;
    }

    public function update(
        $id, $title, $author, $category, $subcategory, 
        $year, $price, $isbn, $description, $link, 
        $images = [], 
        $updatedBy = null 
    ) {
        $sql = "UPDATE books 
                SET title = :title, 
                    author = :author, 
                    category = :category, 
                    subcategory = :subcategory, 
                    year = :year, 
                    price = :price, 
                    isbn = :isbn, 
                    description = :description, 
                    link = :link, 
                    images = :images,
                    updated_by = :updated_by 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':author' => $author,
            ':category' => $category,
            ':subcategory' => $subcategory ?: null,
            ':year' => $year,
            ':price' => $price,
            ':isbn' => $isbn,
            ':description' => $description,
            ':link' => $link,
            ':images' => json_encode($images),
            ':updated_by' => $updatedBy 
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}