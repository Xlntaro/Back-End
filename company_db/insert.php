<?php
require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    
    $sql = "INSERT INTO employees (name, position, salary) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $position, $salary]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Додати працівника</title>
</head>
<body>
    <h2>Додати працівника</h2>
    <form method='POST'>
        <input type='text' name='name' placeholder="Ім'я" required>
        <input type='text' name='position' placeholder='Посада' required>
        <input type='number' step='0.01' name='salary' placeholder='Зарплата' required>
        <button type='submit'>Додати</button>
    </form>
    <br>
    <a href='index.php'>Назад</a>
</body>
</html>
