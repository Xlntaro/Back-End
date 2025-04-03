<?php
require_once 'config.php';

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    send_error('Дозволені лише GET-запити', 405);
}

// Отримання всіх заміток
$stmt = $conn->prepare("SELECT id, title, content, created_at FROM notes ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

$stmt->close();
$conn->close();

// Повернення заміток у форматі JSON
send_success(['notes' => $notes]);