<?php

declare(strict_types=1);

namespace App\Core;

abstract class BaseController
{
    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require dirname(__DIR__, 2) . '/View/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    /**
     * @return array{id: string, username: string}|null
     */
    protected function currentUser(): ?array
    {
        $user = $_SESSION['user'] ?? null;

        if (!is_array($user) || !isset($user['id'], $user['username'])) {
            return null;
        }

        return [
            'id' => (string) $user['id'],
            'username' => (string) $user['username'],
        ];
    }

    /**
     * @return array{id: string, username: string}
     */
    protected function requireAuth(string $redirectPath): array
    {
        $user = $this->currentUser();
        if ($user !== null) {
            return $user;
        }

        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục.';
        $this->redirect($redirectPath);
    }
}
