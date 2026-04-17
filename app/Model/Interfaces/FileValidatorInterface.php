<?php

declare(strict_types=1);

namespace App\Interfaces;

interface FileValidatorInterface
{
    /**
     * @param array<string, mixed> $file
     */
    public function validate(array $file): bool;

    public function getError(): string;
}
