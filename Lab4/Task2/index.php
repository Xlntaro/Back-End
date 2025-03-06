<?php
require_once 'autoload.php';

use Models\User;
use Models\Product;
use Models\Circle; // Додаємо використання класу Circle

$user = new User('john_doe', 'john@example.com');
$product = new Product('Laptop', 999.99);
$circle = new Circle(5, 10, 7); // Створюємо об'єкт Circle

echo "Користувач: " . $user->getUsername() . ", Email: " . $user->getEmail() . "<br>";
echo "Товар: " . $product->getName() . ", Ціна: " . $product->getPrice() . "<br>";
echo $circle . "<br>"; // Виводимо об'єкт Circle

$user->setEmail('john.doe@example.com');
$product->setPrice(1099.99);
$circle->setX(8);
$circle->setY(12);
$circle->setRadius(9);

echo "Оновлений email: " . $user->getEmail() . "<br>";
echo "Оновлена ціна: " . $product->getPrice() . "<br>";
echo $circle . "<br>"; // Виводимо оновлений об'єкт Circle