<?php
// Підключаємо файл з класом Response
require_once 'Response.php';

// Створюємо новий екземпляр класу Response
$response = new Response();

// Налаштовуємо відповідь
$response->setStatus(200)
         ->addHeader("Content-Type: text/html; charset=UTF-8")
         ->addHeader("X-Powered-By: PHP Response Class");

// Готуємо контент
$content = "<!DOCTYPE html>
<html>
<head>
    <title>Відповідь від сервера</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        h1 { color: #336699; }
    </style>
</head>
<body>
    <h1>Вітаємо!</h1>
    <p>Це динамічна відповідь від сервера.</p>
    <p>Поточний час: " . date('H:i:s d.m.Y') . "</p>
</body>
</html>";

// Відправляємо відповідь
$response->send($content);