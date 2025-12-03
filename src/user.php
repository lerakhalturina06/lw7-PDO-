<?php
class User {
    private $id;
    private $name;
    private $email;
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Валидация данных
    public function validate($name, $email) {
        $errors = [];
        
        // Валидация имени
        if (empty($name)) {
            $errors[] = "Имя не может быть пустым";
        } elseif (strlen($name) < 2) {
            $errors[] = "Имя должно содержать минимум 2 символа";
        } elseif (strlen($name) > 100) {
            $errors[] = "Имя не должно превышать 100 символов";
        }
        
        // Валидация email
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
    
    // Добавление пользователя
    public function add($name, $email) {
        $validationErrors = $this->validate($name, $email);
        
        if (!empty($validationErrors)) {
            return [
                'success' => false,
                'errors' => $validationErrors
            ];
        }
        
        try {
            $result = $this->db->addUser($name, $email);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => "Пользователь успешно добавлен! ID: " . $this->db->getConnection()->lastInsertId()
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
    
    // Получение всех пользователей
    public function getAll() {
        return $this->db->getAllUsers();
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
}