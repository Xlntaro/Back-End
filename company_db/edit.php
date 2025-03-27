<?php
require 'db.php';
$id = $_GET['id'];
$sql = "SELECT * FROM employees WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    
    $sql = "UPDATE employees SET name=?, position=?, salary=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $position, $salary, $id]);
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Редагувати працівника</title>
</head>
<body>
    <h2>Редагувати працівника</h2>
    <form method='POST'>
        <input type='text' name='name' value='<?= $employee["name"] ?>' required>
        <input type='text' name='position' value='<?= $employee["position"] ?>' required>
        <input type='number' step='0.01' name='salary' value='<?= $employee["salary"] ?>' required>
        <button type='submit'>Зберегти</button>
    </form>
    <br>
    <a href='index.php'>Назад</a>
</body>
</html>
