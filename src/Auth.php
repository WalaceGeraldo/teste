<?php
namespace App;

class Auth {
    private string $usersFile;
    private array $users;

    public function __construct(string $usersFile) {
        $this->usersFile = $usersFile;
        // Load data on construction (for simplicity)
        if (!file_exists($usersFile)) {
            throw new \Exception("Database file not found: $usersFile");
        }
        $content = file_get_contents($usersFile);
        $this->users = json_decode($content, true) ?? [];
        if (!is_array($this->users)) {
             throw new \Exception("Invalid database format: $usersFile");
        }
    }

    public function login(string $username, string $password): array {
        foreach ($this->users as $user) {
            $dbUser = $user['username'] ?? '';
            $dbPass = $user['password'] ?? '';
            
            if ($dbUser === $username && password_verify($password, $dbPass)) {
                return [
                    'success' => true,
                    'message' => 'Login successful',
                    'role' => $user['role'] ?? 'user'
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Invalid credentials'
        ];
    }
}
