<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\User;

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    try {
        $db = new Database($config);
        $pdo = $db->getConnection();
        
        $userRepo = new App\Repositories\UserRepository($pdo);
        $logger = new App\Services\DatabaseLogger($pdo);
        $authService = new App\Services\AuthService($userRepo, $logger);
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = $authService->login($username, $password);
        
        if ($result['success']) {
            $success = $result['message'];
            session_start();
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    } catch (Exception $e) {
        $error = "Erro no sistema: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema QA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Bem-vindo</h1>
            
            <?php if (isset($error)): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" required placeholder="Seu usuário">
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required placeholder="Sua senha">
                </div>
                
                <button type="submit" name="action" value="login">Entrar</button>
            </form>
            
            <div class="links">
                <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>
</body>
</html>
