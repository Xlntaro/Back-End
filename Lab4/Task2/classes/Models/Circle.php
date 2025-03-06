<?php

class Circle {
    private $x;
    private $y;
    private $radius;

    public function __construct($x, $y, $radius) {
        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }

    public function __toString() {
        return "Коло з центром в ({$this->x}, {$this->y}) і радіусом {$this->radius}";
    }

    // Методи GET
    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function getRadius() {
        return $this->radius;
    }

    // Методи SET
    public function setX($x) {
        $this->x = $x;
    }

    public function setY($y) {
        $this->y = $y;
    }

    public function setRadius($radius) {
        $this->radius = $radius;
    }

    // Метод для перевірки перетину кіл
    public function intersects(Circle $otherCircle) {
        $distance = sqrt(pow($this->x - $otherCircle->getX(), 2) + pow($this->y - $otherCircle->getY(), 2));
        $radiiSum = $this->radius + $otherCircle->getRadius();

        return $distance <= $radiiSum;
    }
}

// Створення об'єктів Circle
$circle1 = new Circle(5, 10, 7);
$circle2 = new Circle(12, 15, 6);
$circle3 = new Circle(20, 25, 3);

// Перевірка перетину кіл
echo $circle1 . "<br>";
echo $circle2 . "<br>";
echo "Перетинаються: " . ($circle1->intersects($circle2) ? "так" : "ні") . "<br><br>";

echo $circle1 . "<br>";
echo $circle3 . "<br>";
echo "Перетинаються: " . ($circle1->intersects($circle3) ? "так" : "ні") . "<br>";