<?php

ob_start();

// Конфігурація
$logFile = 'requests.log';
$rateLimit = 5; // Максимальна кількість запитів
$timeWindow = 60; // Вікно часу у секундах

// Отримуємо IP користувача
$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = time();

// Завантажуємо існуючі записи з лог-файлу
$requests = [];
if (file_exists($logFile)) {
    $logEntries = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($logEntries as $entry) {
        list($loggedIp, $loggedTime) = explode('|', $entry);
        if ($timestamp - $loggedTime <= $timeWindow) {
            $requests[] = [$loggedIp, (int)$loggedTime];
        }
    }
}

// Фільтруємо записи для поточного IP
$recentRequests = array_filter($requests, function ($req) use ($ip) {
    return $req[0] === $ip;
});

// Перевірка обмеження
if (count($recentRequests) >= $rateLimit) {
    http_response_code(429);
    header('Retry-After: ' . $timeWindow);
    echo "Too Many Requests. Please try again later.";
} else {
    // Додаємо новий запис
    $requests[] = [$ip, $timestamp];
    http_response_code(200);
    echo "Request successful!";
}
if (!is_writable(dirname($logFile))) {
    die("Помилка: Немає прав на запис у директорію логів!");
}


// Оновлюємо лог-файл
$logData = array_map(fn($req) => implode('|', $req), $requests);
if (file_put_contents($logFile, implode("\n", $logData) . "\n") === false) {
    die("Помилка: не вдалося записати у файл логів!");
}

ob_end_flush();
?>
