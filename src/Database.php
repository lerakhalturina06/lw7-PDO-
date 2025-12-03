<?php
class Database {
    private static $instance = null;
    private $connection;
    
    // Приватный конструктор для реализации Singleton
    private function __construct() {
        $config = require __DIR__ . '/../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    // Метод для получения единственного экземпляра
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Получение соединения
    public function getConnection() {
        return $this->connection;
    }
    
    // Получение всех пользователей
    public function getAllUsers() {
        $sql = "SELECT id, name, email FROM users";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll();
    }
    
    // Добавление нового пользователя
    public function addUser($name, $email) {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':name' => $name, ':email' => $email]);
    }
    
    // Проверка существования email
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}