<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Робота з масивами</title>
</head>
<body>

<h3>Виведення повторюваних елементів</h3>
<?php
function findDuplicates($array) {
    $counts = array_count_values($array);
    foreach ($counts as $value => $count) {
        if ($count > 1) {
            echo $value . " ";
        }
    }
}

// Приклад масиву
$numbers = [10, 15, 20, 15, 10, 30, 40, 10];
echo "Повторювані елементи: ";
findDuplicates($numbers);
?>

<h3>Генератор імен для тварин</h3>
<?php
function generateName($syllables) {
    $name = '';
    for ($i = 0; $i < 3; $i++) {
        $name .= $syllables[array_rand($syllables)];
    }
    return ucfirst($name);
}

$syllables = ["ba", "ko", "lu", "ma", "zi", "ra"];
echo "Згенероване ім'я: " . generateName($syllables);
?>

<h3>Створення випадкового масиву</h3>
<?php
function createArray() {
    $length = rand(3, 7);
    $array = [];
    for ($i = 0; $i < $length; $i++) {
        $array[] = rand(10, 20);
    }
    return $array;
}

$randomArray = createArray();
echo "Випадковий масив: " . implode(", ", $randomArray);
?>

<h3>Злиття масивів без повторів</h3>
<?php
function mergeArrays($arr1, $arr2) {
    $merged = array_merge($arr1, $arr2);
    $merged = array_unique($merged);
    sort($merged);
    return $merged;
}

$array1 = [10, 20, 30, 40];
$array2 = [20, 30, 50, 60];

$mergedArray = mergeArrays($array1, $array2);
echo "Об'єднаний масив: " . implode(", ", $mergedArray);
?>

<h3>Сортування користувачів</h3>
<?php
function sortUsers($users, $sortBy) {
    if ($sortBy == 'name') {
        ksort($users);
    } elseif ($sortBy == 'age') {
        asort($users);
    }
    return $users;
}

$users = [
    "Ivan" => 25,
    "Maria" => 30,
    "Oleg" => 22,
    "Anna" => 27
];

echo "<p>Сортування за іменем:</p>";
$sortedByName = sortUsers($users, 'name');
foreach ($sortedByName as $name => $age) {
    echo "$name: $age років<br>";
}

echo "<p>Сортування за віком:</p>";
$sortedByAge = sortUsers($users, 'age');
foreach ($sortedByAge as $name => $age) {
    echo "$name: $age років<br>";
}
?>

</body>
</html>
