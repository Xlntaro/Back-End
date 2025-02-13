<?php
$uah = 1500; // Сума в гривнях
$exchangeRate = 29.41; // Наприклад, 1 долар = 29.41 грн
$usd = round($uah / $exchangeRate, 2); // Використовуємо round(), а не roud()

echo "$uah грн. можна обміняти на $usd доларів";
?>

