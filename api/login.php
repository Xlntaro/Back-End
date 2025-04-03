<?php
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Only POST requests are allowed', 405);
}

// Get input data from request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['email']) || !isset($data['password'])) {
    send_error('Missing required fields: email and password');
}

// Sanitize input data
$email = sanitize_input($data['email']);
$password = $data['password']; // Don't sanitize password for verification

// Get user from database
$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    send_error('User not found');
}

$user = $result->fetch_assoc();
$stmt->close();

// Verify password
if (!password_verify($password, $user['password'])) {
    send_error('Invalid password');
}

// Remove password from user data before sending response
unset($user['password']);

// In a real application, you would create a session or token here
// For simplicity, we're just returning success with user data
send_success(['user' => $user], 'Login successful');

$conn->close();