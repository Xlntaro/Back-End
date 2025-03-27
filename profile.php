<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE users SET email=?, phone=?, address=? WHERE id=?");
    $stmt->execute([$email, $phone, $address, $user_id]);

    echo "Дані оновлено!";
}

$stmt = $pdo->prepare("SELECT username, email, phone, address FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<h2>Ваш профіль</h2>
<form action="" method="POST">
    Email: <input type="email" name="email" value="<?= $user['email'] ?>"><br>
    Телефон: <input type="text" name="phone" value="<?= $user['phone'] ?>"><br>
    Адреса: <input type="text" name="address" value="<?= $user['address'] ?>"><br>
    <button type="submit">Оновити</button>
</form>
<a href="logout.php">Вийти</a>
<a href="delete.php">Видалити акаунт</a>
