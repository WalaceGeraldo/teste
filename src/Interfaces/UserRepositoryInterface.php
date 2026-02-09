<?php
namespace App\Interfaces;

interface UserRepositoryInterface {
    public function findByUsername(string $username);
    public function findByEmail(string $email);
    public function create(string $username, string $email, string $passwordHash): bool;
    public function delete(int $id): bool;
}
