<?php
// Завдання 1: Робота з файлами (коментарі)
$filename = "comments.txt";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    
    if (!empty($name) && !empty($comment)) {
        $entry = "$name|$comment" . PHP_EOL;
        file_put_contents($filename, $entry, FILE_APPEND | LOCK_EX);
        header("Location: " . $_SERVER['PHP_SELF']); // Оновлення сторінки для відображення нового коментаря
        exit();
    }
}

$comments = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Коментарі</title>
</head>
<body>
    <h2>Додати коментар</h2>
    <form method="post">
        <label>Ім'я: <input type="text" name="name" required></label><br>
        <label>Коментар: <textarea name="comment" required></textarea></label><br>
        <button type="submit">Надіслати</button>
    </form>
    
    <h2>Список коментарів</h2>
    <?php if (!empty($comments)): ?>
        <table border="1">
            <tr><th>Ім'я</th><th>Коментар</th></tr>
            <?php foreach ($comments as $line): ?>
                <?php 
                $parts = explode('|', $line, 2);
                if (count($parts) === 2):
                    list($name, $comment) = $parts;
                ?>
                <tr><td><?= htmlspecialchars($name) ?></td><td><?= htmlspecialchars($comment) ?></td></tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Коментарів поки немає.</p>
    <?php endif; ?>
</body>
</html>
