<?php
session_start();

// Функції
function my_sin($x) {
    return sin($x);
}

function my_cos($x) {
    return cos($x);
}

function my_tg($x) {
    return (cos($x) == 0) ? "undefined" : sin($x) / cos($x);
}

function xy($x, $y) {
    return pow($x, $y);
}

function factorial($x) {
    if ($x < 0) return "undefined";
    if ($x == 0) return 1;
    return array_product(range(1, $x));
}

// Обробка форми
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $x = $_POST['x'] ?? 0;
    $y = $_POST['y'] ?? 1;

    $_SESSION['x'] = $x;
    $_SESSION['y'] = $y;
    $_SESSION['xy'] = xy($x, $y);
    $_SESSION['factorial'] = factorial($x);
    $_SESSION['my_tg'] = my_tg($x);
    $_SESSION['sin'] = my_sin($x);
    $_SESSION['cos'] = my_cos($x);
    $_SESSION['tg'] = tan($x);
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Калькулятор функцій</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        th { background: yellow; font-weight: bold; }
    </style>
</head>
<body>

<h2>Введіть значення:</h2>
<form method="post">
    <label for="x">Введіть x:</label>
    <input type="number" step="any" name="x" required><br><br>
    
    <label for="y">Введіть y (для x^y):</label>
    <input type="number" step="any" name="y"><br><br>

    <input type="submit" value="Обчислити">
</form>

<?php if (!empty($_SESSION['x'])): ?>
    <h2>Результати обчислень:</h2>
    <table>
        <tr>
            <th>x<sup>y</sup></th>
            <th>x!</th>
            <th>my_tg(x)</th>
            <th>sin(x)</th>
            <th>cos(x)</th>
            <th>tg(x)</th>
        </tr>
        <tr>
            <td><?= $_SESSION['xy'] ?></td>
            <td><?= $_SESSION['factorial'] ?></td>
            <td><?= $_SESSION['my_tg'] ?></td>
            <td><?= $_SESSION['sin'] ?></td>
            <td><?= $_SESSION['cos'] ?></td>
            <td><?= $_SESSION['tg'] ?></td>
        </tr>
    </table>
<?php endif; ?>

</body>
</html>
