<?php

declare(strict_types=1);

namespace App\Validators;

use App\Interfaces\FileValidatorInterface;

#người dùng up file lên lỗi (fail validator), thi file này lấy error message để in ra
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
