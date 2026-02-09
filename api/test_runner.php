<?php
// Visual Unit Test Runner
require_once __DIR__ . '/../src/Auth.php';
use App\Auth;

header('Content-Type: application/json');

// Helper for assertions
function assertTest($name, $condition, $details = []) {
    return [
        'name' => $name,
        'passed' => $condition,
        'details' => $details
    ];
}

$results = [];

// Setup Temporary DB
$testDb = __DIR__ . '/temp_qa_users.json';
$data = [
    ["username" => "admin", "password" => '$2y$12$9qpeD8EBWSVHmbKq7gLRJe7GnUzhi5pB4it4L5K58uqT0EIS33R8a', "role" => "admin"], // pass: 123
    ["username" => "user", "password" => '$2y$12$ax4tMyeaOAWxWP/.f1vzk.ReK229MAXioZw0bxBEXGJzL8N3UZFeq', "role" => "user"], // pass: abc
    ["username" => "locked", "password" => "pass", "role" => "locked_user"] // Future case
];
file_put_contents($testDb, json_encode($data));

try {
    $auth = new Auth($testDb);

    // Test Suite 1: Happy Path
    $res = $auth->login('admin', '123');
    $results[] = assertTest('Login Admin Success', $res['success'] === true && $res['role'] === 'admin');

    $res = $auth->login('user', 'abc');
    $results[] = assertTest('Login User Success', $res['success'] === true && $res['role'] === 'user');

    // Test Suite 2: Security / Negatives
    $res = $auth->login('admin', 'wrong');
    $results[] = assertTest('Block Wrong Password', $res['success'] === false);

    $res = $auth->login('nonexistent', '123');
    $results[] = assertTest('Block Non-existent User', $res['success'] === false);

    // Test Suite 3: Edge Cases
    $res = $auth->login('', '');
    $results[] = assertTest('Block Empty Credentials', $res['success'] === false);
    
    $res = $auth->login('<div>hack</div>', '123');
    $results[] = assertTest('Handle HTML Vectors', $res['success'] === false, ['input' => 'HTML Injection']);

    $res = $auth->login(str_repeat('a', 1000), '123');
    $results[] = assertTest('Handle Long Input', $res['success'] === false, ['input' => '1000 chars']);

} catch (Exception $e) {
    $results[] = assertTest('Critical Error', false, ['error' => $e->getMessage()]);
}

// Cleanup
if (file_exists($testDb)) unlink($testDb);

echo json_encode(['results' => $results]);
