<?php
/**
 * Функція автозавантаження класів із підтримкою неймспейсів.
 * Підключає файли класів із папки 'classes' на основі повного імені класу.
 *
 * @param string $className Повне ім'я класу (наприклад, Models\User)
 * @return void
 */
function autoloadClasses($className) {
    // Формуємо шлях до файлу, враховуючи вкладені папки
    $filePath = __DIR__ . '/classes/' . str_replace('\\', '/', $className) . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        die("❌ Файл для класу {$className} не знайдено! Очікуваний шлях: $filePath");
    }
}

// Реєстрація автозавантажувача
spl_autoload_register('autoloadClasses');
