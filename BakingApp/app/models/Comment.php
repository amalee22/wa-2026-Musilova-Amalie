<?php

class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getByRecipeId(int $recipeId): array {
        $sql = "SELECT c.*, u.username, u.nickname 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.recipe_id = :recipe_id
                ORDER BY c.created_at ASC"; 
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':recipe_id' => $recipeId]);
        $allComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $threaded = [];
        $lookup = [];

        foreach ($allComments as $comment) {
            $comment['replies'] = []; 
            $lookup[$comment['id']] = $comment;
        }

        foreach ($lookup as $id => &$comment) {
            if ($comment['parent_id'] === null) {
                $threaded[] = &$comment;
            } else {
                if (isset($lookup[$comment['parent_id']])) {
                    $lookup[$comment['parent_id']]['replies'][] = &$comment;
                }
            }
        }
        return $threaded;
    }

    // OPRAVA: Přidáno zpracování parent_id
    public function create(int $recipeId, int $userId, string $text, ?int $parentId = null): bool {
        $sql = "INSERT INTO comments (recipe_id, user_id, text, parent_id) VALUES (:recipe_id, :user_id, :text, :parent_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId,
            ':text' => $text,
            ':parent_id' => $parentId
        ]);
    }

    public function update(int $id, string $text): bool {
        $sql = "UPDATE comments SET text = :text WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':text' => $text, ':id' => $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getById(int $id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}