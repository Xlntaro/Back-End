<?php
// Встановлення абсолютного шляху до кореня проєкту
define('ROOT_PATH', __DIR__);

// Функція автозавантаження класів
function customAutoloader($className) {
    // Замінюємо namespace separator на directory separator
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // Повний шлях до файлу класу
    $classFile = ROOT_PATH . DIRECTORY_SEPARATOR . $className . '.php';
    
    // Перевірка існування файлу
    if (file_exists($classFile)) {
        require_once $classFile;
    } else {
        // Додаткова діагностика
        echo "Не вдалося знайти клас: $classFile<br>";
    }
}

// Реєстрація автозавантажувача
spl_autoload_register('customAutoloader');

// Решта коду залишається без змін
use Controllers\UserController;

// Демонстрація використання
try {
    $userController = new UserController();

    // Різні сценарії виклику
    echo $userController->handleUserRegistration('John Doe', 'john@example.com');
    echo "<hr>";
    echo $userController->getUserProfile();
    echo "<hr>";
    echo $userController->listUsers();
} catch (Exception $e) {
    echo "Помилка: " . $e->getMessage();
}