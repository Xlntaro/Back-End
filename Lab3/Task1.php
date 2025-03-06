<?php
// Встановлення cookie при виборі шрифту
if (isset($_GET['size'])) {
    $fontSize = $_GET['size'];
    setcookie('font_size', $fontSize, time() + (86400 * 30), "/"); // Cookie зберігається 30 днів
    header("Location: " . $_SERVER['PHP_SELF']); // Перезавантаження сторінки для застосування стилю
    exit();
}

// Отримання значення cookie, якщо воно є
$fontSize = isset($_COOKIE['font_size']) ? $_COOKIE['font_size'] : '16px';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зміна розміру шрифту</title>
    <style>
        body {
            font-size: <?php echo htmlspecialchars($fontSize); ?>;
        }
        a {
            display: inline-block;
            margin: 10px;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Виберіть розмір шрифту</h1>
    <a href="?size=24px">Великий шрифт</a>
    <a href="?size=16px">Середній шрифт</a>
    <a href="?size=12px">Маленький шрифт</a>
    <p>Цей текст змінюватиме розмір залежно від вибору користувача.</p>
</body>
</html>
