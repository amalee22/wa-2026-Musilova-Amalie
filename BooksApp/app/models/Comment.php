<?php

class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Získání všech komentářů ke knize včetně jména autora
    public function getByBookId(int $bookId): array {
        $sql = "SELECT c.*, u.username, u.nickname 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.book_id = :book_id
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':book_id' => $bookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Přidání nového komentáře
    public function create(int $bookId, int $userId, string $text): bool {
        $sql = "INSERT INTO comments (book_id, user_id, text) VALUES (:book_id, :user_id, :text)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':book_id' => $bookId,
            ':user_id' => $userId,
            ':text' => $text
        ]);
    }
}