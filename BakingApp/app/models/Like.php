<?php
class Like {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function toggleLike(int $userId, int $recipeId): array {
        if ($this->hasLiked($userId, $recipeId)) {
            $stmt = $this->db->prepare("DELETE FROM likes WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$userId, $recipeId]);
            return ['status' => 'unliked'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO likes (user_id, recipe_id) VALUES (?, ?)");
            $stmt->execute([$userId, $recipeId]);
            return ['status' => 'liked'];
        }
    }

    public function hasLiked(int $userId, int $recipeId): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM likes WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        return (bool) $stmt->fetchColumn();
    }

    public function getCount(int $recipeId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        return (int) $stmt->fetchColumn();
    }
}