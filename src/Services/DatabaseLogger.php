<?php
namespace App\Services;

use App\Interfaces\LoggerInterface;
use PDO;

class DatabaseLogger implements LoggerInterface {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function logLogin(?int $userId, bool $success, string $ip) {
        try {
            $stmt = $this->db->prepare("INSERT INTO login_logs (user_id, success, ip_address) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $success ? 1 : 0, $ip]);
        } catch (\PDOException $e) {
            // Logs falharam, mas não deve parar a aplicação
            error_log("Failed to log login attempt: " . $e->getMessage());
        }
    }
}
