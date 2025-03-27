<?php
require 'db.php';
$id = $_GET['id'];
$sql = "DELETE FROM employees WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
header("Location: index.php");
exit;
?>


// stats.php - Статистика
<?php
require 'db.php';
$avgSalary = $pdo->query("SELECT AVG(salary) AS avg_salary FROM employees")->fetch(PDO::FETCH_ASSOC);
$positions = $pdo->query("SELECT position, COUNT(*) AS count FROM employees GROUP BY position")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Статистика</title>
</head>
<body>
    <h2>Статистика</h2>
    <p>Середня зарплата: <?= number_format($avgSalary['avg_salary'], 2) ?> грн</p>
    <h3>Кількість працівників на кожній посаді:</h3>
    <ul>
        <?php foreach ($positions as $position): ?>
            <li><?= $position['position'] ?>: <?= $position['count'] ?></li>
        <?php endforeach; ?>
    </ul>
    <br>
    <a href='index.php'>Назад</a>
</body>
</html>