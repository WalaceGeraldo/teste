<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\QAService;
use App\User;

function clearScreen() {
    # Tenta limpar a tela (funciona na maioria dos terminais Windows/Linux)
    echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; 
    if (DIRECTORY_SEPARATOR === '\\') system('cls'); else system('clear');
}

function waitForInput() {
    echo "\nPressione ENTER para continuar...";
    fgets(STDIN);
}

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    
    $qa = new QAService($pdo);
    
    // Injeção de Dependência Manual
    $userRepo = new App\Repositories\UserRepository($pdo);
    $logger = new App\Services\DatabaseLogger($pdo);
    $authService = new App\Services\AuthService($userRepo, $logger);

    while (true) {
        clearScreen();
        echo "========================================\n";
        echo "      FERRAMENTA QA - CLI (CMD) \n";
        echo "========================================\n";
        echo "[1] Listar Usuários\n";
        echo "[2] Ver Logs de Login Recentes\n";
        echo "[3] Estatísticas do Sistema\n";
        echo "[4] Deletar Usuário\n";
        echo "[5] Rodar Testes Automatizados\n";
        echo "[0] Sair\n";
        echo "========================================\n";
        echo "Escolha uma opção: ";
        
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        $opt = trim($line);

        switch ($opt) {
            case '1':
                echo "\n--- Usuários Cadastrados ---\n";
                $users = $qa->getAllUsers();
                if (empty($users)) { echo "Nenhum usuário encontrado.\n"; }
                foreach ($users as $u) {
                    printf("[%d] %-15s | %s\n", $u['id'], $u['username'], $u['email']);
                }
                waitForInput();
                break;

            case '2':
                echo "\n--- Últimos 10 Logs de Login ---\n";
                $logs = $qa->getLoginLogs(10);
                foreach ($logs as $l) {
                    $status = $l['success'] ? "SUCESSO" : "FALHA";
                    printf("[%s] %s | IP: %s | User: %s\n", 
                        $l['attempt_time'], $status, $l['ip_address'], $l['username'] ?? 'N/A');
                }
                waitForInput();
                break;

            case '3':
                echo "\n--- Estatísticas ---\n";
                $stats = $qa->getSystemStats();
                echo "Total de Usuários: " . $stats['total_users'] . "\n";
                echo "Total de Logins:   " . $stats['total_logins'] . "\n";
                echo "Falhas de Login:   " . $stats['failed_logins'] . "\n";
                waitForInput();
                break;

            case '4':
                echo "\nDigite o ID do usuário para DELETAR: ";
                $id = trim(fgets($handle));
                if (!is_numeric($id)) { echo "ID inválido.\n"; break; }
                
                echo "Tem certeza? (s/n): ";
                $confirm = trim(fgets($handle));
                if (strtolower($confirm) !== 's') { echo "Cancelado.\n"; break; }

                $res = $authService->deleteUser((int)$id);
                echo $res['success'] ? "✅ " . $res['message'] . "\n" : "❌ " . $res['message'] . "\n";
                waitForInput();
                break;
            
            case '5':
                echo "\n--- Rodando Testes Automatizados ---\n";
                // Chama o script de teste existente
                include __DIR__ . '/../tests/run_tests_simple.php';
                waitForInput();
                break;

            case '0':
                echo "Saindo...\n";
                exit;

            default:
                echo "Opção inválida.\n";
                sleep(1);
        }
    }

} catch (Exception $e) {
    echo "Erro Crítico: " . $e->getMessage() . "\n";
}
