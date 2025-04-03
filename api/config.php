<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to file
ini_set('log_errors', 1);
ini_set('error_log', 'php_error.log');
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '36044170Sql'); // Set your password here
define('DB_NAME', 'user_management');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4"); // Додано для підтримки кирилиці

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers for CORS and JSON responses
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Helper function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Helper function to handle errors
function send_error($message, $status_code = 400) {
    http_response_code($status_code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Helper function to send success responses
function send_success($data = null, $message = null) {
    $response = ['success' => true];
    
    if ($message !== null) {
        $response['message'] = $message;
    }
    
    if ($data !== null) {
        $response = array_merge($response, $data);
    }
    
    echo json_encode($response);
    exit;
    
}