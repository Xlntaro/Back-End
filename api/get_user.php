<?php
require_once 'config.php';

// Check if it's a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    send_error('Only GET requests are allowed', 405);
}

// Query to get all users (excluding passwords)
$stmt = $conn->prepare("SELECT id, name, email, created_at FROM users ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

// Fetch all users
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$stmt->close();
$conn->close();

// Return users as JSON
send_success(['users' => $users]);