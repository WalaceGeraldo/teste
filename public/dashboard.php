<?php
session_start();

// Proteção da Rota
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

use App\Database;
use App\QAService;

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    
    // VERIFICAÇÃO DE SEGURANÇA: O usuário ainda existe no banco?
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        // Usuário foi deletado! Derrubar sessão.
        session_destroy();
        header('Location: index.php?error=deleted');
        exit;
    }

    $qa = new QAService($pdo);
    
    $stats = $qa->getSystemStats();
    $users = $qa->getAllUsers();
    $logs = $qa->getLoginLogs();
    
} catch (Exception $e) {
    die("Erro ao carregar dashboard: " . $e->getMessage());
}

function getStatusBadge($success) {
    if ($success) {
        return '<span class="px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Sucesso</span>';
    }
    return '<span class="px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">Falha</span>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistema Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-indigo-600">Dashboard</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-500 text-sm">Olá, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                        <button onclick="window.location.reload()" class="text-gray-500 hover:text-indigo-600 text-sm font-medium">
                            🔄 Atualizar
                        </button>
                        <a href="logout.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white overflow-hidden shadow rounded-lg transform transition hover:scale-105">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Usuários</dt>
                                        <dd class="text-3xl font-semibold text-gray-900"><?= $stats['total_users'] ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Logins -->
                    <div class="bg-white overflow-hidden shadow rounded-lg transform transition hover:scale-105">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Logins</dt>
                                        <dd class="text-3xl font-semibold text-gray-900"><?= $stats['total_logins'] ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Failed Logins -->
                    <div class="bg-white overflow-hidden shadow rounded-lg transform transition hover:scale-105">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Falhas de Login</dt>
                                        <dd class="text-3xl font-semibold text-gray-900"><?= $stats['failed_logins'] ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                     <!-- Login Logs Table -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">🔍 Monitoramento de Acesso</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Log de tentativas em tempo real.</p>
                            </div>
                        </div>
                        <div class="border-t border-gray-200">
                            <ul role="list" class="divide-y divide-gray-200 h-96 overflow-y-auto">
                                <?php foreach ($logs as $log): ?>
                                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-indigo-600 truncate">
                                            <?= htmlspecialchars($log['username'] ?? 'Desconhecido') ?>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <?= getStatusBadge($log['success']) ?>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                IP: <?= htmlspecialchars($log['ip_address']) ?>
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <p>
                                                <?= date('d/m/Y H:i:s', strtotime($log['attempt_time'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Users List & Chart -->
                    <div class="space-y-8">
                        <!-- Chart -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Taxa de Sucesso vs Falha</h3>
                            <div style="height: 200px;">
                                <canvas id="loginChart"></canvas>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">👥 Usuários Recentes</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach (array_slice($users, 0, 5) as $user): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#<?= $user['id'] ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></div>
                                                <div class="text-sm text-gray-500 text-xs"><?= htmlspecialchars($user['email']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('loginChart').getContext('2d');
        const loginChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sucesso', 'Falha'],
                datasets: [{
                    data: [
                        <?= $stats['total_logins'] - $stats['failed_logins'] ?>, 
                        <?= $stats['failed_logins'] ?>
                    ],
                    backgroundColor: [
                        '#10B981', // Green
                        '#EF4444'  // Red
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { usePointStyle: true }
                    }
                }
            }
        });
    </script>
</body>
</html>
