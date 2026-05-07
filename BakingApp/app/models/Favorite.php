<?php
class Favorite {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function toggleFavorite(int $userId, int $recipeId): array {
        if ($this->hasFavorited($userId, $recipeId)) {
            $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$userId, $recipeId]);
            return ['status' => 'unfavorited'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)");
            $stmt->execute([$userId, $recipeId]);
            return ['status' => 'favorited'];
        }
    }

    public function hasFavorited(int $userId, int $recipeId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        return (bool) $stmt->fetchColumn();
    }
}