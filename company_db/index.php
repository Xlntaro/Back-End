<?php
require 'db.php';
$sql = "SELECT * FROM employees";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Список працівників</title>
</head>
<body>
    <h2>Працівники</h2>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Ім'я</th>
            <th>Посада</th>
            <th>Зарплата</th>
            <th>Дії</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['position'] ?></td>
                <td><?= $row['salary'] ?></td>
                <td>
                    <a href='edit.php?id=<?= $row['id'] ?>'>Редагувати</a>
                    <a href='delete.php?id=<?= $row['id'] ?>' onclick="return confirm('Видалити працівника?')">Видалити</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href='insert.php'>Додати працівника</a>
    <br><br>
    <a href='stats.php'>Переглянути статистику</a>
</body>
</html>