<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\User;

if ($argc < 2) {
    echo "Uso: php scripts/delete_user.php [ID_DO_USUARIO]\n";
    echo "Exemplo: php scripts/delete_user.php 5\n";
    exit(1);
}

$id = (int)$argv[1];

try {
    echo "Conectando ao banco de dados...\n";
    $db = new Database($config);
    $userModel = new User($db->getConnection());

    echo "Tentando excluir usuario ID: $id ...\n";
    $result = $userModel->delete($id);

    if ($result['success']) {
        echo "✅ SUCESSO: " . $result['message'] . "\n";
        echo "A alteração foi SALVA automaticamente no MySQL.\n";
    } else {
        echo "❌ ERRO: " . $result['message'] . "\n";
    }

} catch (Exception $e) {
    echo "Erro crítico: " . $e->getMessage() . "\n";
}
