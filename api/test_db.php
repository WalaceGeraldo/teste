<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$file = __DIR__ . '/../data/users.json';
echo "Checking file: $file\n";

if (!file_exists($file)) {
    echo "File not found!\n";
    exit(1);
}

$content = file_get_contents($file);
echo "Content length: " . strlen($content) . "\n";

$data = json_decode($content, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Error: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "Users found: " . count($data) . "\n";
print_r($data);
