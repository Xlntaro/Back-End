<?php
// Увімкнення відображення помилок для діагностики
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Налаштування
$baseDir = 'users/'; // Базова директорія для створення папок користувачів
$message = ''; // Повідомлення для користувача
$messageType = ''; // Тип повідомлення (success/error)

// Перевірка, чи існує базова директорія
if (!file_exists($baseDir)) {
    if (!mkdir($baseDir, 0777, true)) {
        $message = "Не вдалося створити базову директорію '$baseDir'. Перевірте права доступу.";
        $messageType = 'error';
    } else {
        chmod($baseDir, 0777); // Встановлюємо права для створення підпапок
    }
}

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Отримання даних з форми
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Перевірка валідності введених даних
    if (empty($login) || empty($password)) {
        $message = "Логін та пароль обов'язкові для заповнення";
        $messageType = 'error';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $message = "Логін може містити лише латинські літери, цифри та знак підкреслення";
        $messageType = 'error';
    } else {
        $userDir = $baseDir . $login . '/';
        
        // Перевірка, чи існує вже папка з таким ім'ям
        if (file_exists($userDir)) {
            $message = "Користувач з логіном '$login' вже існує!";
            $messageType = 'error';
        } else {
            // Створення папки користувача
            if (mkdir($userDir, 0777, true)) {
                // Створення підпапок
                mkdir($userDir . 'video', 0777);
                mkdir($userDir . 'music', 0777);
                mkdir($userDir . 'photo', 0777);
                
                // Створення прикладів файлів
                file_put_contents($userDir . 'video/example.mp4', 'Приклад відео файлу');
                file_put_contents($userDir . 'music/example.mp3', 'Приклад аудіо файлу');
                file_put_contents($userDir . 'photo/example.jpg', 'Приклад фото файлу');
                
                // Збереження пароля
                file_put_contents($userDir . 'password.txt', password_hash($password, PASSWORD_DEFAULT));
                
                $message = "Користувач '$login' успішно створений з усіма необхідними папками та файлами";
                $messageType = 'success';
            } else {
                $message = "Помилка при створенні папки користувача. Перевірте права доступу.";
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
} else {
    $message = "Базова директорія '$baseDir' недоступна.";
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Створення папки користувача</title>
    <style>
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
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Створення папки користувача</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <h2>Створити нового користувача</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="login">Логін:</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Створити</button>
            </form>
        </div>
        
        <div class="links">
            <a href="delete.php" class="link">Перейти до видалення папки користувача</a>
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