<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;

try {
    $db = new Database($config);
    $pdo = $db->getConnection();

    echo "=== USUÁRIOS NO BANCO DE DADOS (MySQL) ===\n";
    
    $stmt = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();

    if (empty($users)) {
        echo "Nenhum usuário encontrado.\n";
    } else {
        printf("%-5s | %-20s | %-30s | %-20s\n", "ID", "Usuário", "Email", "Data Criação");
        echo str_repeat("-", 80) . "\n";
        
        foreach ($users as $user) {
            printf("%-5d | %-20s | %-30s | %-20s\n", 
                $user['id'], 
                substr($user['username'], 0, 20), 
                substr($user['email'], 0, 30), 
                $user['created_at']
            );
        }
    }
    echo "\nTotal: " . count($users) . " usuários.\n";

} catch (Exception $e) {
    echo "Erro ao conectar: " . $e->getMessage() . "\n";
}
