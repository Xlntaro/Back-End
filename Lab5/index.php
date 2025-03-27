<?php
require 'db.php';
$sql = "SELECT * FROM tov";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Список товарів</title>
</head>
<body>
    <h2>Товари</h2>
    <table border='1'>
        <tr>
            <th>ID</th>
            <th>Назва</th>
            <th>Вартість</th>
            <th>Кількість</th>
            <th>Дата</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['cost'] ?></td>
                <td><?= $row['kol'] ?></td>
                <td><?= $row['date'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href='insert.php'>Додати товар</a>
    <br><br>
    <form action='delete.php' method='POST'>
        <input type='number' name='id' placeholder='ID товару' required>
        <button type='submit'>Видалити</button>
    </form>
</body>
</html>

