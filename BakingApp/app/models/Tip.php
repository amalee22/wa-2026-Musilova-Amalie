<?php
class Tip {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $sql = "SELECT t.*, u.username, u.nickname 
                FROM tips t 
                JOIN users u ON t.created_by = u.id 
                ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $title, string $content, string $icon, int $userId): bool {
        $sql = "INSERT INTO tips (title, content, icon, created_by) VALUES (:title, :content, :icon, :created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':icon' => $icon,
            ':created_by' => $userId
        ]);
    }
}