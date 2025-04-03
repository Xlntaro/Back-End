<?php
$servername = "localhost";
$username = "root";
$password = "36044170Sql"; // Замінити на твій пароль
$dbname = "user_management";

// Підключення
$conn = new mysqli($servername, $username, $password, $dbname);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
} else {
    echo "Успішне підключення до бази даних!";
}
$conn->close();
?>
