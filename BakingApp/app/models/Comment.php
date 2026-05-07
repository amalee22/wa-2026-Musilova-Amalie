<?php

class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Získání všech komentářů k jednomu receptu (včetně jména autora)
    public function getByRecipeId(int $recipeId): array {
        $sql = "SELECT c.*, u.username, u.nickname 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.recipe_id = :recipe_id
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':recipe_id' => $recipeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Přidání nového komentáře
    public function create(int $recipeId, int $userId, string $text): bool {
        $sql = "INSERT INTO comments (recipe_id, user_id, text) VALUES (:recipe_id, :user_id, :text)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId,
            ':text' => $text
        ]);
    }

    // 3. Smazání komentáře (bude moci udělat jen autor nebo admin)
    public function delete(int $id): bool {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Získání jednoho komentáře (pro kontrolu práv před smazáním)
    public function getById(int $id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}