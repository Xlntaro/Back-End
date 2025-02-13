<?php
$char = 'e'; // Задаємо символ (можна змінити на будь-яку букву)

$char = strtolower($char); // Перетворюємо у нижній регістр для універсальності

switch ($char) {
    case 'a':
    case 'e':
    case 'i':
    case 'o':
    case 'u':
        echo "$char - це голосна літера.";
        break;
    case 'b': case 'c': case 'd': case 'f': case 'g': case 'h': case 'j': case 'k': case 'l': case 'm': 
    case 'n': case 'p': case 'q': case 'r': case 's': case 't': case 'v': case 'w': case 'x': case 'y': case 'z':
        echo "$char - це приголосна літера.";
        break;
    default:
        echo "$char - це не літера або некоректний символ.";
}
?>
