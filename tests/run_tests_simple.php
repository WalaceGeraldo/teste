<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Services\DatabaseLogger;

function assertTest($condition, $message) {
    if ($condition) {
        echo "✅ PASS: $message\n";
    } else {
        echo "❌ FAIL: $message\n";
    }
}

echo "=== INICIANDO TESTES QA (Modo SOLID) ===\n";

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    
    // Configurando Dependências
    $userRepo = new UserRepository($pdo);
    $logger = new DatabaseLogger($pdo);
    $authService = new AuthService($userRepo, $logger);

    $testUser = 'qa_user_' . time();
    $testEmail = 'qa_' . time() . '@example.com';
    $password = 'password123';

    // Teste 1: Cadastro
    echo "\n--- Teste de Cadastro ---\n";
    $result = $authService->register($testUser, $testEmail, $password);
    assertTest($result['success'] === true, "Usuário criado via AuthService");

    // Teste 2: Cadastro Duplicado
    $result = $authService->register($testUser, $testEmail, $password);
    assertTest($result['success'] === false, "AuthService impediu duplicidade");

    // Teste 3: Login Sucesso (Com Username)
    echo "\n--- Teste de Login ---\n";
    $result = $authService->login($testUser, $password);
    assertTest($result['success'] === true, "Login com username OK");
    assertTest(isset($result['user_id']), "ID retornado pelo Service");

    // Teste 4 Login Sucesso (Com Email - Nova Funcionalidade)
    $result = $authService->login($testEmail, $password);
    assertTest($result['success'] === true, "Login com EMAIL OK (Nova Feature)");

    // Teste 5: Login Falha
    $result = $authService->login($testUser, 'wrongpass');
    assertTest($result['success'] === false, "Login recusado corretamente");

    // Cleanup
    echo "\n--- Limpeza ---\n";
    $id = $result['user_id'] ?? null;
    if ($id) {
        // Testando delete do service também
        $delResult = $authService->deleteUser($id);
        assertTest($delResult['success'] === true, "Usuário de teste deletado via Service");
    } else {
        // Fallback manual se login falhou
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$testUser]);
        echo "Limpeza manual realizada.\n";
    }

    echo "\n=== TODOS OS TESTES FINALIZADOS ===\n";

} catch (Exception $e) {
    echo "ERRO CRÍTICO NOS TESTES: " . $e->getMessage() . "\n";
}
