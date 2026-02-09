<?php
// Simple Unit Test runner without dependencies
require_once __DIR__ . '/../../src/Auth.php';

use App\Auth;

echo "Running tests...\n";

// Setup
$testDb = __DIR__ . '/test_users.json';
$data = [
    ["username" => "testadmin", "password" => '$2y$12$WLisO1hhsa2jffn/GrlYruJsukXpgT0CkMvsNJSVJPP6MG/5zpXAm', "role" => "admin"],
    ["username" => "testuser", "password" => '$2y$12$Zw8BxrV0VbqIu5Xadl9iK.52oaH83fy4w/4Sn5Qrp5r0aWdD5C9Ry', "role" => "user"]
];
file_put_contents($testDb, json_encode($data));

$auth = new Auth($testDb);

// Test 1: Successful Login
$result = $auth->login('testadmin', 'pass123');
if ($result['success'] === true && $result['role'] === 'admin') {
    echo "PASS: Login Admin\n";
} else {
    echo "FAIL: Login Admin\n";
    print_r($result);
}

// Test 2: Failed Login
$result = $auth->login('testadmin', 'wrongpass');
if ($result['success'] === false) {
    echo "PASS: Login Failed (Wrong Password)\n";
} else {
    echo "FAIL: Login Failed (Wrong Password)\n";
    print_r($result);
}

// Test 3: Non-existent User
$result = $auth->login('notauser', 'pass123');
if ($result['success'] === false) {
    echo "PASS: Login Failed (No User)\n";
} else {
    echo "FAIL: Login Failed (No User)\n";
    print_r($result);
}

// Cleanup
if (file_exists($testDb)) {
    unlink($testDb);
}
echo "Done.\n";
