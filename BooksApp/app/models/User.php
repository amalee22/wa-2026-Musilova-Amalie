<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // 1. Registrace nového uživatele
    public function register(
        string $username, 
        string $email, 
        string $password, 
        ?string $firstName = null, 
        ?string $lastName = null, 
        ?string $nickname = null
    ): bool {
        // Kontrola, zda uživatel s tímto emailem už neexistuje
        if ($this->findByEmail($email)) {
            return false; // Email už je zabraný
        }

        // ZABEZPEČENÍ: Vytvoření bezpečného hashe z hesla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL dotaz rozšířen o nová pole
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, nickname) 
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword, // Do DB ukládáme hash, nikoliv čisté heslo!
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ]);
    }

    // 2. Nalezení uživatele podle emailu (použijeme při přihlašování)
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        // Vrátí pole s daty uživatele, nebo false pokud neexistuje
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 3. Získání uživatele podle ID (hodí se pro zobrazení profilu atd.)
    public function findById(int $id) {
        $sql = "SELECT id, username, email, first_name, last_name, nickname, created_at FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Trvalé smazání uživatele podle ID
    public function delete(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}