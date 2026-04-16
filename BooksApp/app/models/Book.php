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
    // PŘIDÁNO: created_by do INSERT INTO a :created_by do VALUES
    $sql = "INSERT INTO books (title, author, isbn, category, subcategory, year, price, link, description, images, created_by) 
            VALUES (:title, :author, :isbn, :category, :subcategory, :year, :price, :link, :description, :images, :created_by)";
    
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
    
    $imagesJson = json_encode($data['images'] ?? []);
    $stmt->bindParam(':images', $imagesJson);

    // PŘIDÁNO: Navázání hodnoty created_by (z pole $data, které jsme si poslali z Controlleru)
    $stmt->bindParam(':created_by', $data['created_by']);

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
        $year, $price, $isbn, $description, $link, $images = [], $updatedBy = null // Přidán parametr
    ) {
        // Přidáno updated_by = :updated_by do SQL
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
            ':updated_by' => $updatedBy // Uložení ID uživatele
        ]);
    }

    // Trvalé smazání knihy z databáze
    public function delete($id) {
        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }



}