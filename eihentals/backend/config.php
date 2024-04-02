<?php
class Database {
    private $host = 'localhost';
    private $db   = 'eihents';
    private $user = 'root';
    private $pass = 'root';
    private $charset = 'utf8mb4';

    private $pdo;
    private $dsn;
    private $opt;

    public function __construct() {
        $this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $this->opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $this->opt);
    }

    public function getConnection() {
        return $this->pdo;
    }
}

// Lietot šo klasi
$db = new Database();
$pdo = $db->getConnection();
?>
