<?php

namespace App;

use App\Services\AuthService;
use App\QAService;

class ApiController {
    private $authService;
    private $qaService;

    public function __construct(AuthService $authService, ?QAService $qaService = null) {
        $this->authService = $authService;
        $this->qaService = $qaService;
    }

    private function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    public function handleRequest($method, $route) {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        // Rotas de Autenticação (AuthService)
        if ($route === 'auth/login' && $method === 'POST') {
            $username = $input['username'] ?? '';
            $password = $input['password'] ?? '';
            
            // Login agora aceita email também automaticamente pela AuthService
            $result = $this->authService->login($username, $password);
            
            if ($result['success']) {
                $this->jsonResponse($result, 200);
            } else {
                $this->jsonResponse($result, 401);
            }
        }

        if ($route === 'auth/register' && $method === 'POST') {
            $username = $input['username'] ?? '';
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            
            $result = $this->authService->register($username, $email, $password);

            if ($result['success']) {
                $this->jsonResponse($result, 201);
            } else {
                $this->jsonResponse($result, 400);
            }
        }

        // Rotas de Usuários (QA e Auth)
        if ($route === 'users' && $method === 'GET') {
            if (!$this->qaService) {
                $this->jsonResponse(['error' => 'Service not available'], 500);
            }
            $users = $this->qaService->getAllUsers();
            $this->jsonResponse($users);
        }

        if (preg_match('/^users\/(\d+)$/', $route, $matches) && $method === 'DELETE') {
            $id = (int)$matches[1];
            
            // DELETE agora via AuthService
            $result = $this->authService->deleteUser($id);
            
            if ($result['success']) {
                $this->jsonResponse($result, 200);
            } else {
                $this->jsonResponse($result, 404);
            }
        }

        // Rotas de Estatísticas
        if ($route === 'stats' && $method === 'GET') {
            if (!$this->qaService) {
                $this->jsonResponse(['error' => 'Service not available'], 500);
            }
            $stats = $this->qaService->getSystemStats();
            $this->jsonResponse($stats);
        }

        $this->jsonResponse(['error' => 'Endpoint not found'], 404);
    }
}
