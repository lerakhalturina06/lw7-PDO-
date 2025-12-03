<?php
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/User.php';
require_once __DIR__ . '/src/ConsoleCommand.php';

// Обработка аргументов командной строки
if (php_sapi_name() !== 'cli') {
    die("Этот скрипт может быть запущен только из командной строки\n");
}

$command = new ConsoleCommand();

// Проверка аргументов
if ($argc < 2) {
    $command->help();
    exit(1);
}

$action = $argv[1];

switch ($action) {
    case 'add':
        $command->addUser();
        break;
    case 'list':
        $command->showAllUsers();
        break;
    case 'help':
        $command->help();
        break;
    default:
        echo "Неизвестная команда: {$action}\n";
        $command->help();
        exit(1);
}