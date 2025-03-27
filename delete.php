<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$user_id]);

session_destroy();
header("Location: index.php");
exit();
