<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Робота з рядками</title>
</head>
<body>

<!-- Заміна символів -->
<h3>Заміна символів</h3>
<form method="POST">
    Текст: <input type="text" name="text"><br>
    Знайти: <input type="text" name="find"><br>
    Замінити: <input type="text" name="replace"><br>
    <input type="submit" value="Заміна">
</form>

<?php
if ($_POST) {
    if (isset($_POST['text']) && isset($_POST['find']) && isset($_POST['replace'])) {
        $text = $_POST['text'];
        $find = $_POST['find'];
        $replace = $_POST['replace'];
        echo "<p>Результат: " . str_replace($find, $replace, $text) . "</p>";
    }
}
?>

<!-- Перестановка назв міст -->
<h3>Перестановка назв міст</h3>
<form method="POST">
    Введіть міста через пробіл: <input type="text" name="cities"><br>
    <input type="submit" value="Сортувати">
</form>

<?php
if ($_POST && isset($_POST['cities'])) {
    $cities = explode(" ", $_POST['cities']);
    sort($cities);
    echo "<p>Відсортовані міста: " . implode(" ", $cities) . "</p>";
}
?>

<!-- Виділення імені файлу без розширення -->
<h3>Виділення імені файлу без розширення</h3>
<?php
$file = "D:/WebServers/home/testsite/www/myfile.txt";
$info = pathinfo($file);
echo "<p>Ім'я файлу без розширення: " . $info['filename'] . "</p>";
?>

<!-- Кількість днів між датами -->
<h3>Кількість днів між датами</h3>
<?php
$date1 = new DateTime('2015-02-10');
$date2 = new DateTime('2015-03-10');
$diff = $date1->diff($date2);
echo "<p>Кількість днів: " . $diff->days . "</p>";
?>

<!-- Генератор паролів -->
<h3>Генератор паролів</h3>
<?php
function generatePassword($length) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    return substr(str_shuffle($chars), 0, $length);
}
echo "<p>Згенерований пароль: " . generatePassword(12) . "</p>";
?>

<!-- Перевірка міцності пароля -->
<h3>Перевірка міцності пароля</h3>
<?php
function checkPassword($password) {
    if (preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/\d/', $password) && preg_match('/[\W_]/', $password) && strlen($password) >= 8) {
        return "Пароль міцний";
    } else {
        return "Пароль не міцний";
    }
}
echo "<p>Перевірка пароля: " . checkPassword("TestPassword123!") . "</p>";
?>

</body>
</html>
