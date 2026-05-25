<?php

class Recipe {
    private $db;

    public function __construct($db) { $this->db = $db; }

    public function create(string $title, string $description, string $ingredients, string $instructions, int $category, int $prep_time, array $images, int $userId): bool {
        $sql = "INSERT INTO recipes (title, description, ingredients, instructions, category_id, prep_time, images, created_by) 
                VALUES (:title, :description, :ingredients, :instructions, :category, :prep_time, :images, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':title' => $title, ':description' => $description, ':ingredients' => $ingredients, ':instructions' => $instructions, ':category' => $category, ':prep_time' => $prep_time, ':images' => json_encode($images), ':created_by' => $userId]);
    }
public function getAll(string $sort = 'latest'): array {
        // Dynamické sestavení řazení
        $orderClause = "ORDER BY r.created_at DESC"; // Výchozí: Nejnovější
        if ($sort === 'oldest') { $orderClause = "ORDER BY r.created_at ASC"; }
        if ($sort === 'time') { $orderClause = "ORDER BY r.prep_time ASC"; }

        $sql = "SELECT r.*, c.name as category_name, u.username, u.nickname 
                FROM recipes r 
                LEFT JOIN categories c ON r.category_id = c.id 
                LEFT JOIN users u ON r.created_by = u.id 
                $orderClause";
        $stmt = $this->db->prepare($sql); $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $query, string $sort = 'latest'): array {
        $orderClause = "ORDER BY r.created_at DESC";
        if ($sort === 'oldest') { $orderClause = "ORDER BY r.created_at ASC"; }
        if ($sort === 'time') { $orderClause = "ORDER BY r.prep_time ASC"; }

        $sql = "SELECT r.*, c.name as category_name, u.username, u.nickname 
                FROM recipes r 
                LEFT JOIN categories c ON r.category_id = c.id 
                LEFT JOIN users u ON r.created_by = u.id 
                WHERE r.title LIKE :q OR r.description LIKE :q OR r.ingredients LIKE :q 
                $orderClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':q' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getById(int $id): ?array {
        $sql = "SELECT r.*, c.name as category_name, u.username as author_name FROM recipes r LEFT JOIN categories c ON r.category_id = c.id LEFT JOIN users u ON r.created_by = u.id WHERE r.id = :id";
        $stmt = $this->db->prepare($sql); $stmt->execute([':id' => $id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC); return $recipe ?: null;
    }

    public function getByUserId(int $userId): array {
        $sql = "SELECT r.*, c.name as category_name FROM recipes r LEFT JOIN categories c ON r.category_id = c.id WHERE r.created_by = :user_id ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql); $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLikedByUserId(int $userId): array {
        $sql = "SELECT r.*, c.name as category_name FROM recipes r JOIN likes l ON r.id = l.recipe_id LEFT JOIN categories c ON r.category_id = c.id WHERE l.user_id = :user_id ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql); $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $title, $description, $ingredients, $instructions, $category, $prep_time, $images = []) {
        $sql = "UPDATE recipes SET title = :title, description = :description, ingredients = :ingredients, instructions = :instructions, category_id = :category, prep_time = :prep_time, images = :images WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':title' => $title, ':description' => $description, ':ingredients' => $ingredients, ':instructions' => $instructions, ':category' => $category, ':prep_time' => $prep_time, ':images' => json_encode($images)]);
    }

    public function delete($id) {
        $sql = "DELETE FROM recipes WHERE id = :id";
        $stmt = $this->db->prepare($sql); return $stmt->execute([':id' => $id]);
    }
}