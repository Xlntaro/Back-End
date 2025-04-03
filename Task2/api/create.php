<?php
require_once 'config.php';

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Дозволені лише POST-запити', 405);
}

// Отримання даних із запиту
$data = json_decode(file_get_contents('php://input'), true);

// Перевірка наявності обов’язкових полів
if (!isset($data['title']) || !isset($data['content'])) {
    send_error('Заголовок і текст замітки обов’язкові');
}

// Санітизація даних
$title = sanitize_input($data['title']);
$content = sanitize_input($data['content']);

// Перевірка, чи поля не порожні після санітизації
if (empty($title) || empty($content)) {
    send_error('Заголовок і текст не можуть бути порожніми');
}

// Додавання замітки до бази даних
$stmt = $conn->prepare("INSERT INTO notes (title, content) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $content);

if ($stmt->execute()) {
    send_success(['note_id' => $stmt->insert_id], 'Замітку успішно створено');
} else {
    send_error('Помилка створення замітки: ' . $conn->error);
}

$stmt->close();
$conn->close();