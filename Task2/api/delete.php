<?php
require_once 'config.php';

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Дозволені лише POST-запити', 405);
}

// Отримання даних із запиту
$data = json_decode(file_get_contents('php://input'), true);

// Перевірка наявності ID
if (!isset($data['id'])) {
    send_error('ID замітки обов’язковий');
}

// Санітизація ID
$id = (int)$data['id'];

// Видалення замітки
$stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        send_success(null, 'Замітку успішно видалено');
    } else {
        send_error('Замітку не знайдено');
    }
} else {
    send_error('Помилка видалення замітки: ' . $conn->error);
}

$stmt->close();
$conn->close();