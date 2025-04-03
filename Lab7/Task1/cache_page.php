<?php
// Шлях до файлу кешу
$cacheFile = 'cache.html';

// Перевіряємо, чи існує кеш і чи не запитана сторінка з помилкою
if (file_exists($cacheFile) && !isset($_GET['error'])) {
    // Віддаємо кешований вміст
    echo file_get_contents($cacheFile);
    exit; // Завершуємо виконання скрипта
}

// Функція для генерації контенту сторінки
function generatePageContent() {
    // Визначаємо статус-код залежно від параметра error
    $status = (isset($_GET['error']) && $_GET['error'] == '404') ? 404 : 200;
    
    // Встановлюємо HTTP статус-код
    http_response_code($status);
    
    // Генеруємо вміст залежно від статус-коду
    if ($status === 200) {
        return "<h1>Вітаємо!</h1><p>Це успішно згенерована сторінка</p><p>Час генерації: " . date('H:i:s') . "</p>";
    } else {
        return "<h1>404 Not Found</h1><p>Сторінку не знайдено</p>";
    }
}

// Починаємо буферизацію виводу
ob_start();

// Генеруємо вміст сторінки
$content = generatePageContent();
echo $content; // Виводимо контент

// Отримуємо поточний статус-код
$statusCode = http_response_code();

// Обробляємо кешування залежно від статус-коду
if ($statusCode === 200) {
    // Отримуємо вміст буфера
    $bufferedContent = ob_get_contents();
    
    // Завершуємо буферизацію, виводимо вміст
    ob_end_flush();
    
    // Зберігаємо у файл кешу
    file_put_contents($cacheFile, $bufferedContent);
} else if ($statusCode === 404) {
    // Завершуємо буферизацію, виводимо вміст
    ob_end_flush();
    
    // Видаляємо файл кешу, якщо він існує
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
} else {
    // Для інших статус-кодів просто виводимо вміст
    ob_end_flush();
}
?>