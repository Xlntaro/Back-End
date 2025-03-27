<?php
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $cost = $_POST['cost'];
    $kol = $_POST['kol'];
    $date = $_POST['date'];
    
    $sql = "INSERT INTO tov (name, cost, kol, date) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $cost, $kol, $date]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Додати товар</title>
</head>
<body>
    <h2>Додати товар</h2>
    <form method='POST'>
        <input type='text' name='name' placeholder='Назва' required>
        <input type='number' name='cost' placeholder='Вартість' required>
        <input type='number' name='kol' placeholder='Кількість' required>
        <input type='date' name='date' required>
        <button type='submit'>Додати</button>
    </form>
    <br>
    <a href='index.php'>Назад</a>
</body>
</html>

