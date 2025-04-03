<?php
// Розпочинаємо буферизацію виводу
ob_start();

// Функція отримання поточного URI
function getCurrentUri() {
    // Отримуємо URI запиту
    $uri = $_SERVER['REQUEST_URI'];
    
    // Видаляємо параметри GET запиту, якщо вони є
    $uri = strtok($uri, '?');
    
    // Видаляємо завершальний слеш, якщо він є
    $uri = rtrim($uri, '/');
    
    return $uri;
}

// Функція обробки перенаправлень
function handleRedirects() {
    // Шлях до файлу з перенаправленнями
    $redirectsFile = 'redirects.json';
    
    // Перевіряємо, чи існує файл
    if (!file_exists($redirectsFile)) {
        // Якщо файлу немає, виводимо повідомлення про помилку
        http_response_code(500);
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>Файл конфігурації перенаправлень відсутній.</p>";
        return false;
    }
    
    try {
        // Зчитуємо та декодуємо JSON файл з перенаправленнями
        $redirects = json_decode(file_get_contents($redirectsFile), true);
        
        // Перевіряємо, чи вдалося декодувати JSON
        if ($redirects === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Помилка декодування JSON: " . json_last_error_msg());
        }
        
        // Отримуємо поточний URI
        $currentUri = getCurrentUri();
        
        // Перевіряємо, чи є поточний URI у списку перенаправлень
        if (isset($redirects[$currentUri])) {
            $target = $redirects[$currentUri];
            
            // Перевіряємо тип перенаправлення
            if ($target === '/404') {
                // Для спеціального випадку '/404' віддаємо 404 статус
                http_response_code(404);
                echo "<h1>404 Not Found</h1>";
                echo "<p>Запитана сторінка не знайдена або більше не доступна.</p>";
            } else {
                // Для звичайного перенаправлення
                // Очищуємо буфер перед відправкою заголовків
                ob_clean();
                
                // Відправляємо заголовок перенаправлення 301
                header("Location: {$target}", true, 301);
                exit; // Завершуємо виконання скрипта
            }
            
            return true;
        }
        
        // Якщо перенаправлення не знайдено, відображаємо нормальний контент
        return false;
        
    } catch (Exception $e) {
        // Обробляємо помилку, якщо щось пішло не так
        http_response_code(500);
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>Виникла помилка при обробці перенаправлень: " . $e->getMessage() . "</p>";
        return false;
    }
}

// Обробляємо перенаправлення
$redirected = handleRedirects();

// Якщо перенаправлення не відбулося, виводимо нормальний контент
if (!$redirected) {
    // Встановлюємо статус 200 OK
    http_response_code(200);
    echo "<h1>Ласкаво просимо!</h1>";
    echo "<p>Це звичайна сторінка без перенаправлення.</p>";
    echo "<p>Поточний URI: " . getCurrentUri() . "</p>";
}

// Завершуємо буферизацію та відправляємо вміст
ob_end_flush();
?>