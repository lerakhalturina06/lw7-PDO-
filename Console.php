<?php
declare(strict_types=1);

require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/User.php';
require_once __DIR__ . '/src/ConsoleCommand.php';

// Проверка запуска из командной строки
if (php_sapi_name() !== 'cli') {
    die("Этот скрипт может быть запущен только из командной строки\n");
}

$command = new ConsoleCommand();

// Проверка аргументов командной строки
if ($argc < 2) {
    $command->help();
    exit(1);
}

$action = $argv[1];

try {
    switch ($action) {
        case 'add':
            $command->addUser();
            break;
        case 'list':
            $command->showAllUsers();
            break;
        case 'show':
            if ($argc < 3) {
                echo "Ошибка: необходимо указать ID пользователя\n";
                echo "Использование: php Console.php show <id>\n";
                exit(1);
            }
            $id = (int)$argv[2];
            $command->showUserById($id);
            break;
        case 'help':
            $command->help();
            break;
        default:
            echo "Неизвестная команда: {$action}\n";
            $command->help();
            exit(1);
    }
} catch (Throwable $e) {
    echo " Произошла ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
