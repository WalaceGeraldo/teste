<?php

namespace App;

use PDO;

class QAService {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getLoginLogs($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.username 
            FROM login_logs l 
            LEFT JOIN users u ON l.user_id = u.id 
            ORDER BY l.attempt_time DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSystemStats() {
        return [
            'total_users' => $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'total_logins' => $this->db->query("SELECT COUNT(*) FROM login_logs")->fetchColumn(),
            'failed_logins' => $this->db->query("SELECT COUNT(*) FROM login_logs WHERE success = 0")->fetchColumn()
        ];
    }
}
