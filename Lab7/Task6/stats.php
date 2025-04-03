<?php
// Database configuration (same as in traffic_logger.php)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '36044170Sql');
define('DB_NAME', 'traffic_logs');

// Function to get 404 statistics
function get404Stats() {
    try {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $oneDayAgo = date('Y-m-d H:i:s', strtotime('-1 day'));
        
        // Get total requests
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM traffic_logs WHERE request_time >= :one_day_ago");
        $stmt->execute([':one_day_ago' => $oneDayAgo]);
        $totalRequests = $stmt->fetchColumn();
        
        // Get 404 errors
        $stmt = $pdo->prepare("SELECT COUNT(*) as errors FROM traffic_logs 
                              WHERE request_time >= :one_day_ago AND http_status = 404");
        $stmt->execute([':one_day_ago' => $oneDayAgo]);
        $errorCount = $stmt->fetchColumn();
        
        // Get top 404 URLs
        $stmt = $pdo->prepare("SELECT requested_url, COUNT(*) as count 
                              FROM traffic_logs 
                              WHERE request_time >= :one_day_ago AND http_status = 404
                              GROUP BY requested_url 
                              ORDER BY count DESC 
                              LIMIT 10");
        $stmt->execute([':one_day_ago' => $oneDayAgo]);
        $top404Urls = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'total_requests' => $totalRequests,
            'error_count' => $errorCount,
            'error_percentage' => $totalRequests > 0 ? ($errorCount / $totalRequests) * 100 : 0,
            'top_404_urls' => $top404Urls
        ];
        
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

// Get statistics
$stats = get404Stats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Statistics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .stats { background: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .alert { color: #d8000c; background-color: #ffd2d2; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Traffic Statistics (Last 24 Hours)</h1>
    
    <div class="stats">
        <h2>Overview</h2>
        <p>Total Requests: <?php echo number_format($stats['total_requests']); ?></p>
        <p>404 Errors: <?php echo number_format($stats['error_count']); ?></p>
        <p>404 Error Percentage: <?php echo round($stats['error_percentage'], 2); ?>%</p>
        
        <?php if ($stats['error_percentage'] > 10): ?>
            <div class="alert">
                Warning: 404 error rate exceeds 10% threshold!
            </div>
        <?php endif; ?>
    </div>
    
    <div class="stats">
        <h2>Top 404 URLs</h2>
        <?php if (!empty($stats['top_404_urls'])): ?>
            <table>
                <tr>
                    <th>URL</th>
                    <th>Count</th>
                </tr>
                <?php foreach ($stats['top_404_urls'] as $url): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($url['requested_url']); ?></td>
                        <td><?php echo $url['count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No 404 errors in the last 24 hours.</p>
        <?php endif; ?>
    </div>
</body>
</html>