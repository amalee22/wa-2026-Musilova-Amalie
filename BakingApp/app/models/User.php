<?php

class User {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    public function register(string $username, string $email, string $password, ?string $firstName = null, ?string $lastName = null, ?string $nickname = null): bool {
        if ($this->findByEmail($email)) return false;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, nickname) VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hashedPassword, ':first_name' => $firstName, ':last_name' => $lastName, ':nickname' => $nickname]);
    }

    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql); $stmt->execute([':email' => $email]); return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findById(int $id) {
        $sql = "SELECT id, username, email, first_name, last_name, nickname, bio, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql); $stmt->execute([':id' => $id]); return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NOVÉ: Načtení všech uživatelů
    public function getAllUsers(): array {
        $sql = "SELECT id, username, nickname, first_name, last_name, bio FROM users ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql); $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NOVÉ: Vyhledávání uživatelů
    public function searchUsers(string $query): array {
        $sql = "SELECT id, username, nickname, first_name, last_name, bio FROM users WHERE username LIKE :q OR nickname LIKE :q OR first_name LIKE :q OR last_name LIKE :q ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql); $stmt->execute([':q' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProfile(int $id, array $data): bool {
        $sql = "UPDATE users SET nickname = :nickname, first_name = :first_name, last_name = :last_name, bio = :bio WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':nickname' => $data['nickname'], ':first_name' => $data['first_name'], ':last_name' => $data['last_name'], ':bio' => $data['bio']]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql); return $stmt->execute([':id' => $id]);
    }
}