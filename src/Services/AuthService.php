<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Interfaces\LoggerInterface;

class AuthService {
    private $userRepo;
    private $logger;

    public function __construct(UserRepository $userRepo, LoggerInterface $logger) {
        $this->userRepo = $userRepo;
        $this->logger = $logger;
    }

    public function register(string $username, string $email, string $password): array {
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Todos os campos são obrigatórios.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido.'];
        }

        if ($this->userRepo->findByUsername($username)) {
            return ['success' => false, 'message' => 'Usuário já existe.'];
        }

        if ($this->userRepo->findByEmail($email)) {
            return ['success' => false, 'message' => 'Email já cadastrado.'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        if ($this->userRepo->create($username, $email, $hash)) {
            return ['success' => true, 'message' => 'Cadastro realizado com sucesso!'];
        }

        return ['success' => false, 'message' => 'Erro ao salvar no banco.'];
    }

    public function login(string $identifier, string $password): array {
        // Tenta achar por usuario OU email (flexibilidade)
        $user = $this->userRepo->findByUsername($identifier);
        
        if (!$user) {
            $user = $this->userRepo->findByEmail($identifier);
        }

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->logAttempt($user['id'], true);
            return [
                'success' => true, 
                'message' => 'Login realizado!',
                'user_id' => $user['id'],
                'username' => $user['username']
            ];
        }

        $this->logAttempt(null, false);
        return ['success' => false, 'message' => 'Credenciais inválidas.'];
    }

    public function deleteUser(int $id): array {
        if ($this->userRepo->delete($id)) {
            return ['success' => true, 'message' => 'Usuário deletado.'];
        }
        return ['success' => false, 'message' => 'Erro ao deletar ou usuário não encontrado.'];
    }

    private function logAttempt(?int $userId, bool $success) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->logger->logLogin($userId, $success, $ip);
    }
}
