<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\User;
use App\QAService;
use App\ApiController;

// Configurar cabeçalhos CORS (se necessário para frontend externo)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    
    $userRepo = new App\Repositories\UserRepository($pdo);
    $logger = new App\Services\DatabaseLogger($pdo);
    $authService = new App\Services\AuthService($userRepo, $logger);
    
    $qaService = new QAService($pdo);
    
    $api = new ApiController($authService, $qaService);
    
    // Roteamento simples: pega o parâmetro 'route' da URL ou usa path info
    // Exemplo: /api.php?route=users ou /api.php/users
    $route = $_GET['route'] ?? '';
    
    // Se estiver usando servidor embutido ou .htaccess que passa path info
    if (empty($route) && isset($_SERVER['PATH_INFO'])) {
        $route = trim($_SERVER['PATH_INFO'], '/');
    }

    $method = $_SERVER['REQUEST_METHOD'];
    
    $api->handleRequest($method, $route);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
