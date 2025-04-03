<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '36044170Sql');
define('DB_NAME', 'traffic_logs');

// Administrator email for alerts
define('ADMIN_EMAIL', 'admin@example.com');

// Function to log traffic
function logTraffic() {
    try {
        // Get request details
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $requestTime = date('Y-m-d H:i:s');
        $requestedUrl = $_SERVER['REQUEST_URI'];
        $httpStatus = http_response_code();
        
        // Connect to database
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insert log entry
        $stmt = $pdo->prepare("INSERT INTO traffic_logs (ip_address, request_time, requested_url, http_status) 
                              VALUES (:ip_address, :request_time, :requested_url, :http_status)");
        $stmt->execute([
            ':ip_address' => $ipAddress,
            ':request_time' => $requestTime,
            ':requested_url' => $requestedUrl,
            ':http_status' => $httpStatus
        ]);
        
        // Check if we need to send an alert (do this only occasionally to avoid spamming)
        if (rand(1, 100) <= 10) { // 10% chance to check
            checkForErrorSpike($pdo);
        }
        
    } catch (PDOException $e) {
        // Log error to file if database connection fails
        error_log("Traffic logger error: " . $e->getMessage(), 3, "traffic_logger_errors.log");
    }
}

// Function to check for error spikes
function checkForErrorSpike($pdo) {
    $oneDayAgo = date('Y-m-d H:i:s', strtotime('-1 day'));
    
    // Get total requests in last 24 hours
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM traffic_logs WHERE request_time >= :one_day_ago");
    $stmt->execute([':one_day_ago' => $oneDayAgo]);
    $totalRequests = $stmt->fetchColumn();
    
    if ($totalRequests > 0) {
        // Get 404 errors in last 24 hours
        $stmt = $pdo->prepare("SELECT COUNT(*) as errors FROM traffic_logs 
                              WHERE request_time >= :one_day_ago AND http_status = 404");
        $stmt->execute([':one_day_ago' => $oneDayAgo]);
        $errorCount = $stmt->fetchColumn();
        
        $errorPercentage = ($errorCount / $totalRequests) * 100;
        
        if ($errorPercentage > 10) {
            // Send email alert
            $subject = "High 404 Error Rate Alert";
            $message = "Warning: 404 errors account for " . round($errorPercentage, 2) . "% of total requests in the last 24 hours.\n\n";
            $message .= "Total requests: $totalRequests\n";
            $message .= "404 errors: $errorCount";
            
            mail(ADMIN_EMAIL, $subject, $message);
        }
    }
}

// Log this request
logTraffic();
?>