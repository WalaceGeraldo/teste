<?php
namespace App\Interfaces;

interface LoggerInterface {
    public function logLogin(?int $userId, bool $success, string $ip);
}
