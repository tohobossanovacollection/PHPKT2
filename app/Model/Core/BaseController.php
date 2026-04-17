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
}
