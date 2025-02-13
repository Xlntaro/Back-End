<?php
function generateTable($rows, $cols) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    for ($i = 0; $i < $rows; $i++) {
        echo "<tr>";
        for ($j = 0; $j < $cols; $j++) {
            $color = sprintf("#%06X", mt_rand(0, 0xFFFFFF)); // Генеруємо випадковий колір
            echo "<td style='width: 50px; height: 50px; background-color: $color;'></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Виклик функції для таблиці 5x5 (можна змінити параметри)
generateTable(5, 5);
?>
