<?php
class Database {
    private $host = "localhost";
    private $db_name = "db_s2_ETU004156";
    private $username = "ETU004156";
    private $password = "4HdgEDaD";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}