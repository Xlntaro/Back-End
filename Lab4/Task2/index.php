<?php
require_once 'autoload.php';

use Models\User;
use Models\Product;

$user = new User('john_doe', 'john@example.com');
$product = new Product('Laptop', 999.99);

echo "Користувач: " . $user->getUsername() . ", Email: " . $user->getEmail() . "<br>";
echo "Товар: " . $product->getName() . ", Ціна: " . $product->getPrice() . "<br>";

$user->setEmail('john.doe@example.com');
$product->setPrice(1099.99);

echo "Оновлений email: " . $user->getEmail() . "<br>";
echo "Оновлена ціна: " . $product->getPrice() . "<br>";
