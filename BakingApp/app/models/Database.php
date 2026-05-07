<?php

class Database {
    private $host = "localhost";
    private $db_name = "wa_projekt_peceni";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // PŘIDÁNO: charset=utf8mb4 pro správné fungování češtiny (háčky, čárky i emoji)
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Chyba připojení: " . $exception->getMessage();
        }
        return $this->conn;
    }
}