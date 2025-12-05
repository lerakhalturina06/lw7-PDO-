<?php
declare(strict_types=1);

class ConsoleCommand {
    private User $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    /**
     * Команда добавления пользователя
     */
    public function addUser(): void {
        echo "=== Добавление нового пользователя ===\n";
        
        // Получение данных из консоли
        echo "Введите имя: ";
        $name = trim(fgets(STDIN) ?: '');
        
        echo "Введите email: ";
        $email = trim(fgets(STDIN) ?: '');
        
        // Добавление пользователя
        $result = $this->user->add($name, $email);
        
        if ($result['success']) {
            echo "\n " . $result['message'] . "\n";
            if (isset($result['id'])) {
                echo "   ID нового пользователя: " . $result['id'] . "\n";
            }
        } else {
            echo "\n Ошибки:\n";
            foreach ($result['errors'] as $error) {
                echo "   - {$error}\n";
            }
        }
    }
    
    /**
     * Команда вывода всех пользователей
     */
    public function showAllUsers(): void {
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
            echo str_pad((string)$user['id'], 5) . " | "
                 . str_pad($user['name'], 20) . " | "
                 . str_pad($user['email'], 30) . "\n";
        }
    }
    
    /**
     * Команда получения пользователя по ID
     * @param int $id ID пользователя
     */
    public function showUserById(int $id): void {
        $user = $this->user->getById($id);
        
        if ($user === null) {
            echo " Пользователь с ID {$id} не найден.\n";
            return;
        }
        
        echo "=== Пользователь #{$id} ===\n";
        echo "ID:    " . $user['id'] . "\n";
        echo "Имя:   " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
    }
    
    /**
     * Команда помощи
     */
    public function help(): void {
        echo "Доступные команды:\n";
        echo "  php Console.php add          - Добавить нового пользователя\n";
        echo "  php Console.php list         - Показать всех пользователей\n";
        echo "  php Console.php show <id>    - Показать пользователя по ID\n";
        echo "  php Console.php help         - Показать эту справку\n";
    }
}
