<?php
// Налаштування
$baseDir = 'users/'; // Базова директорія для папок користувачів
$message = ''; // Повідомлення для користувача
$messageType = ''; // Тип повідомлення (success/error)

// Рекурсивна функція для видалення папки з усім вмістом
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    
    // Перевірка валідності введених даних
    if (empty($login) || empty($password)) {
        $message = "Логін та пароль обов'язкові для заповнення";
        $messageType = 'error';
    } else {
        $userDir = $baseDir . $login . '/';
        
        // Перевірка, чи існує папка з таким ім'ям
        if (!file_exists($userDir)) {
            $message = "Користувач з логіном '$login' не існує!";
            $messageType = 'error';
        } else {
            // Перевірка пароля
            $passwordFile = $userDir . 'password.txt';
            if (file_exists($passwordFile)) {
                $storedHash = file_get_contents($passwordFile);
                if (password_verify($password, $storedHash)) {
                    // Видалення папки користувача з усім вмістом
                    if (deleteDirectory($userDir)) {
                        $message = "Папка користувача '$login' успішно видалена з усім вмістом";
                        $messageType = 'success';
                    } else {
                        $message = "Помилка при видаленні папки користувача. Перевірте права доступу.";
                        $messageType = 'error';
                    }
                } else {
                    $message = "Невірний пароль для користувача '$login'";
                    $messageType = 'error';
                }
            } else {
                $message = "Файл з паролем не знайдено. Спробуйте видалити папку вручну.";
                $messageType = 'error';
            }
        }
    }
}

// Отримання списку існуючих користувачів
$users = [];
if (file_exists($baseDir) && is_dir($baseDir)) {
    $files = scandir($baseDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_dir($baseDir . $file)) {
            $users[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалення папки користувача</title>
    <style>
        /* Ваш CSS код без змін */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        
        h1, h2 {
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #c82333;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .message.success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .message.error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        
        .user-list {
            margin-top: 20px;
        }
        
        .user-item {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        
        .links {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        
        .link {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .link:hover {
            background-color: #0056b3;
        }
        
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Видалення папки користувача</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="warning">
            <strong>Увага!</strong> Видалення папки користувача призведе до незворотної втрати всіх даних у цій папці.
        </div>
        
        <div class="form-container">
            <h2>Видалити папку користувача</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="login">Логін:</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Видалити</button>
            </form>
        </div>
        
        <div class="links">
            <a href="index.php" class="link">Повернутися до створення користувачів</a>
        </div>
        
        <?php if (!empty($users)): ?>
            <div class="user-list">
                <h2>Існуючі користувачі</h2>
                <?php foreach ($users as $user): ?>
                    <div class="user-item">
                        <?php echo htmlspecialchars($user); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>