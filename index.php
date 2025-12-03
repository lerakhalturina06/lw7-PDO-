<?php
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/User.php';

$user = new User();

// Обработка POST-запроса для добавления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    
    $result = $user->add($name, $email);
}

// Получение всех пользователей
$users = $user->getAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление пользователями</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 300px; padding: 8px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добавить нового пользователя</h1>
        
        <?php if (isset($result)): ?>
            <?php if ($result['success']): ?>
                <div class="success"><?= htmlspecialchars($result['message']) ?></div>
            <?php else: ?>
                <div class="error">
                    <?php foreach ($result['errors'] as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="name" required maxlength="100">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required maxlength="255">
            </div>
            <button type="submit">Добавить пользователя</button>
        </form>
        
        <h2>Все пользователи</h2>
        <?php if (empty($users)): ?>
            <p>Нет пользователей</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                </tr>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>