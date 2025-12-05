<?php
declare(strict_types=1);

class User {
    private int $id;
    private string $name;
    private string $email;
    private Database $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Валидация данных пользователя
     * @param string $name Имя пользователя
     * @param string $email Email пользователя
     * @return array Массив ошибок, пустой если валидация прошла успешно
     */
    public function validate(string $name, string $email): array {
        $errors = [];
        
        // Валидация имени
        $name = trim($name);
        if (empty($name)) {
            $errors[] = "Имя не может быть пустым";
        } elseif (strlen($name) < 2) {
            $errors[] = "Имя должно содержать минимум 2 символа";
        } elseif (strlen($name) > 100) {
            $errors[] = "Имя не должно превышать 100 символов";
        }
        
        // Валидация email
        $email = trim($email);
        if (empty($email)) {
            $errors[] = "Email не может быть пустым";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный формат email";
        } elseif (strlen($email) > 255) {
            $errors[] = "Email не должен превышать 255 символов";
        } elseif ($this->db->emailExists($email)) {
            $errors[] = "Этот email уже зарегистрирован";
        }
        
        return $errors;
    }
    
    /**
     * Добавление нового пользователя
     * @param string $name Имя пользователя
     * @param string $email Email пользователя
     * @return array Результат операции
     */
    public function add(string $name, string $email): array {
        $validationErrors = $this->validate($name, $email);
        
        if (!empty($validationErrors)) {
            return [
                'success' => false,
                'errors' => $validationErrors
            ];
        }
        
        try {
            $result = $this->db->addUser(trim($name), trim($email));
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => "Пользователь успешно добавлен!",
                    'id' => (int)$this->db->getConnection()->lastInsertId()
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => ['Ошибка при добавлении пользователя']
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'errors' => ['Ошибка базы данных: ' . $e->getMessage()]
            ];
        }
    }
    
    /**
     * Получение всех пользователей
     * @return array Массив пользователей
     */
    public function getAll(): array {
        return $this->db->getAllUsers();
    }
    
    /**
     * Получение пользователя по ID
     * @param int $id ID пользователя
     * @return array|null Данные пользователя или null если не найден
     */
    public function getById(int $id): ?array {
        $sql = "SELECT id, name, email FROM users WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        
        return $result !== false ? $result : null;
    }
    
    /**
     * Очистка всех пользователей (только для тестов)
     * @return array Результат операции
     */
    public function clearAll(): array {
        try {
            $this->db->clearUsers();
            return [
                'success' => true,
                'message' => 'Все пользователи удалены'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'errors' => ['Ошибка при очистке: ' . $e->getMessage()]
            ];
        }
    }
    
    // Getters с типами
    public function getId(): int {
        return $this->id;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
}
