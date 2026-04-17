<?php

declare(strict_types=1);

namespace App\Validators;

use App\Interfaces\FileValidatorInterface;

abstract class AbstractFileValidator implements FileValidatorInterface
{
    protected string $error = '';

    public function getError(): string
    {
        return $this->error;
    }

    protected function setError(string $message): void
    {
        $this->error = $message;
    }
}
