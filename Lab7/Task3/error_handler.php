<?php
// Початок буферизації виводу
ob_start();

// Реєстрація функції, яка виконається при завершенні скрипта
register_shutdown_function('handleFatalError');

// Основна функція обробки фатальних помилок
function handleFatalError() {
    // Отримуємо останню помилку
    $error = error_get_last();
    
    // Перевіряємо, чи є помилка і чи вона фатальна (E_ERROR)
    if ($error !== null && $error['type'] === E_ERROR) {
        // Очищаємо буфер виводу
        ob_clean();
        
        // Встановлюємо HTTP статус 500
        http_response_code(500);
        
        // Визначаємо орієнтовний час вирішення (наприклад, через 2 години)
        $resolutionTime = date('H:i', strtotime('+2 hours'));
        
        // Виводимо кастомну сторінку помилки
        echo '<!DOCTYPE html>
        <html lang="uk">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>500 Internal Server Error</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f8f8;
                    color: #333;
                    margin: 0;
                    padding: 20px;
                    text-align: center;
                }
                .container {
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 30px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #e74c3c;
                }
                .resolution-time {
                    font-weight: bold;
                    color: #3498db;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>500 Internal Server Error</h1>
                <p>Перепрошуємо, на сервері сталася помилка.</p>
                <p>Наша технічна команда вже працює над вирішенням проблеми.</p>
                <p>Очікуваний час вирішення: <span class="resolution-time">' . $resolutionTime . '</span></p>
                <p>Дякуємо за розуміння.</p>
            </div>
        </body>
        </html>';
    } else {
        // Якщо немає фатальних помилок, встановлюємо статус 200 OK
        http_response_code(200);
        
        // Завершуємо буфер, відправляємо контент
        ob_end_flush();
    }
}

// Тут може бути основний код сторінки
echo '<h1>Вітаємо на нашому сайті</h1>';
echo '<p>Ця сторінка відображається нормально, оскільки немає помилок.</p>';

// Для тестування фатальної помилки, розкоментуйте наступний рядок:
 nonexistentFunction(); // Виклик неіснуючої функції спричинить E_ERROR

?>