<?php
declare(strict_types=1);

class Database {
    private static ?self $instance = null;
    private PDO $connection;
    
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
            throw new RuntimeException("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    public function getAllUsers(): array {
        $sql = "SELECT id, name, email FROM users";
        $stmt = $this->connection->query($sql);
        $result = $stmt->fetchAll();
        
        return $result !== false ? $result : [];
    }
    
    public function addUser(string $name, string $email): bool {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':name' => $name, ':email' => $email]);
    }
    
    public function emailExists(string $email): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
    
    public function clearUsers(): void {
        $this->connection->exec("DELETE FROM users");
        $this->connection->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    }
    
    // Предотвращаем клонирование и десериализацию
    private function __clone() {}
    public function __wakeup() {
        throw new RuntimeException("Cannot unserialize singleton");
    }
}
