<?php
require_once 'config.php';

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Дозволені лише POST-запити', 405);
}

// Отримання даних із запиту
$data = json_decode(file_get_contents('php://input'), true);

// Перевірка наявності обов’язкових полів
if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
    send_error('ID, заголовок і текст замітки обов’язкові');
}

// Санітизація даних
$id = (int)$data['id'];
$title = sanitize_input($data['title']);
$content = sanitize_input($data['content']);

// Перевірка, чи поля не порожні
if (empty($title) || empty($content)) {
    send_error('Заголовок і текст не можуть бути порожніми');
}

// Оновлення замітки у базі даних
$stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
$stmt->bind_param("ssi", $title, $content, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        send_success(null, 'Замітку успішно оновлено');
    } else {
        send_error('Замітку не знайдено або дані не змінено');
    }
} else {
    send_error('Помилка оновлення замітки: ' . $conn->error);
}

$stmt->close();
$conn->close();