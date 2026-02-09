<?php
// Simple Integration Test for PHP API endpoints
$host = 'http://localhost:8000';
$endpoint = '/api/login.php';

echo "Integration Test: $host$endpoint\n";

// Ensure server is running
$socket = @fsockopen("localhost", 8000, $errno, $errstr, 2);
if (!$socket) {
    die("ERROR: Server not running! Please run 'run_app.bat' first.\n");
}
fclose($socket);

// Test 1: POST Login
$data = json_encode(['username' => 'admin', 'password' => 'password123']);
$opts = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => $data,
        'ignore_errors' => true
    ]
];
$context  = stream_context_create($opts);
$response = file_get_contents($host . $endpoint, false, $context);
$status_line = $http_response_header[0];

if (strpos($status_line, '200') !== false && strpos($response, 'success') !== false) {
    echo "PASS: Login Endpoint returned 200 OK + Success JSON\n";
} else {
    echo "FAIL: Login Endpoint ($status_line)\nOutput: $response\n";
}
