<?php
$month = 3; // Задаємо номер місяця (наприклад, 3 - березень)

if ($month == 12 || $month == 1 || $month == 2) {
    $season = "Зима";
} elseif ($month >= 3 && $month <= 5) {
    $season = "Весна";
} elseif ($month >= 6 && $month <= 8) {
    $season = "Літо";
} elseif ($month >= 9 && $month <= 11) {
    $season = "Осінь";
} else {
    $season = "Невідомий місяць";
}

echo "Місяць №$month відповідає сезону: $season";
?>
