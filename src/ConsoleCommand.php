<?php
class ConsoleCommand {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    // Команда для добавления пользователя
    public function addUser() {
        echo "=== Добавление нового пользователя ===\n";
        
        // Получение данных из консоли
        echo "Введите имя: ";
        $name = trim(fgets(STDIN));
        
        echo "Введите email: ";
        $email = trim(fgets(STDIN));
        
        // Добавление пользователя
        $result = $this->user->add($name, $email);
        
        if ($result['success']) {
            echo "\n✅ " . $result['message'] . "\n";
        } else {
            echo "\n❌ Ошибки:\n";
            foreach ($result['errors'] as $error) {
                echo " - {$error}\n";
            }
        }
    }
    
    // Команда для вывода всех пользователей
    public function showAllUsers() {
        $users = $this->user->getAll();
        
        if (empty($users)) {
            echo "В базе данных нет пользователей.\n";
            return;
        }
        
        echo "=== Список пользователей ===\n";
        echo str_pad("ID", 5) . " | " 
             . str_pad("Имя", 20) . " | " 
             . str_pad("Email", 30) . "\n";
        echo str_repeat("-", 60) . "\n";
        
        foreach ($users as $user) {
            echo str_pad($user['id'], 5) . " | "
                 . str_pad($user['name'], 20) . " | "
                 . str_pad($user['email'], 30) . "\n";
        }
    }
    
    // Помощь по командам
    public function help() {
        echo "Доступные команды:\n";
        echo "  php console.php add    - Добавить нового пользователя\n";
        echo "  php console.php list   - Показать всех пользователей\n";
        echo "  php console.php help   - Показать эту справку\n";
    }
}