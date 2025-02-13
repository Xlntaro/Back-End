<?php
// Генеруємо випадкове тризначне число
$number = mt_rand(100, 999);
echo "Випадкове число: $number <br>";

// Розбиття числа на цифри
$hundreds = floor($number / 100);
$tens = floor(($number % 100) / 10);
$units = $number % 10;

// 1. Обчислення суми цифр
$sum = $hundreds + $tens + $units;
echo "Сума цифр: $sum <br>";

// 2. Число у зворотному порядку
$reversed = $units * 100 + $tens * 10 + $hundreds;
echo "Число у зворотному порядку: $reversed <br>";

// 3. Найбільше можливе число
$digits = [$hundreds, $tens, $units];
rsort($digits); // Сортуємо у спадаючому порядку
$maxNumber = $digits[0] * 100 + $digits[1] * 10 + $digits[2];
echo "Найбільше можливе число: $maxNumber <br>";
?>
