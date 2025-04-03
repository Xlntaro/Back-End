<?php
// Налаштування бази даних
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '36044170Sql'); // Вкажіть ваш пароль для MySQL
define('DB_NAME', 'notes_app');

// Створення підключення
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Перевірка підключення
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Налаштування кодування
$conn->set_charset("utf8mb4");

// Налаштування заголовків для CORS і JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Функція для санітизації даних
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Функція для надсилання помилок
function send_error($message, $status_code = 400) {
    http_response_code($status_code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Функція для надсилання успішної відповіді
function send_success($data = null, $message = null) {
    $response = ['success' => true];
    if ($message !== null) {
        $response['message'] = $message;
    }
    if ($data !== null) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit;
}