<?php
session_start();

// Обробка форми входу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($login === 'Admin' && $password === 'password') {
        $_SESSION['user'] = 'Admin';
    } else {
        $error = 'Невірний логін або пароль';
    }
}

// Обробка виходу
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація</title>
</head>
<body>
    <?php if (isset($_SESSION['user'])): ?>
        <h1>Добрий день, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
        <a href="?logout=1">Вийти</a>
    <?php else: ?>
        <h2>Форма авторизації</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="login">Логін:</label>
            <input type="text" name="login" id="login" required>
            <br>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Увійти</button>
        </form>
    <?php endif; ?>
</body>
</html>
