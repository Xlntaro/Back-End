<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<h2>Вітаємо, {$_SESSION['username']}!</h2>";
    echo "<a href='profile.php'>Редагувати профіль</a> | <a href='logout.php'>Вийти</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід</title>
</head>
<body>
    <h2>Вхід</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Логін" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit">Увійти</button>
    </form>
    <br>
    <a href="register.php">Реєстрація</a>
</body>
</html>
