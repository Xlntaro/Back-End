<?php
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Only POST requests are allowed', 405);
}

// Get input data from request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    send_error('Missing required fields: name, email, and password');
}

// Sanitize input data
$name = sanitize_input($data['name']);
$email = sanitize_input($data['email']);
$password = $data['password']; // Will be hashed, no need to sanitize

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_error('Invalid email format');
}

// Validate password length
if (strlen($password) < 6) {
    send_error('Password must be at least 6 characters long');
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    send_error('Email already in use');
}
$stmt->close();

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password_hash);

if ($stmt->execute()) {
    send_success(['user_id' => $stmt->insert_id], 'User registered successfully');
} else {
    send_error('Registration failed: ' . $conn->error);
}

$stmt->close();
$conn->close();