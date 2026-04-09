<?php
require_once 'Database.php';

class Book {
    //definice, že proměnná $db musí být vždy instancí třídy PDO
    private PDO $db; //je dobré úvádět ten PDO princip

    public function __construct() { //to co začíná _ znamená něco striktně pro php
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO books (title, author, isbn, category, subcategory, year, price, link, description, images) 
                VALUES (:title, :author, :isbn, :category, :subcategory, :year, :price, :link, :description, :images)";
        
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':author', $data['author']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':subcategory', $data['subcategory']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':link', $data['link']);
        $stmt->bindParam(':description', $data['description']);
        
        // Přidáno uložení obrázků ve formátu JSON
        $imagesJson = json_encode($data['images'] ?? []);
        $stmt->bindParam(':images', $imagesJson);

        return $stmt->execute();
    }


        // Metoda pro načtení všech knih
    public function getAll() {
        $sql = "SELECT * FROM books ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();



        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


// Získání jedné konkrétní knihy podle jejího ID
    public function getById($id) {
        $sql = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizace existující knihy
    public function update(
        $id, $title, $author, $category, $subcategory, 
        $year, $price, $isbn, $description, $link, $images = []
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
                    images = :images
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
            ':images' => json_encode($images)
        ]);
    }

    // Trvalé smazání knihy z databáze
    public function delete($id) {
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }



}