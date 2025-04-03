<?php
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_error('Only POST requests are allowed', 405);
}

// Get input data from request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['id']) || !isset($data['name']) || !isset($data['email'])) {
    send_error('Missing required fields: id, name, and email');
}

// Sanitize input data
$id = (int)$data['id'];
$name = sanitize_input($data['name']);
$email = sanitize_input($data['email']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_error('Invalid email format');
}

// Check if email already exists for a different user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    send_error('Email already in use by another user');
}
$stmt->close();

// Update user in database
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
$stmt->bind_param("ssi", $name, $email, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        send_success(null, 'User updated successfully');
    } else {
        send_error('No changes made or user not found');
    }
} else {
    send_error('Update failed: ' . $conn->error);
}

$stmt->close();
$conn->close();