<?php

declare(strict_types=1);

namespace App\Validators;

final class SizeValidator extends AbstractFileValidator
{
    public function __construct(private int $maxBytes)
    {
    }

    public function validate(array $file): bool
    {
        $size = (int) ($file['size'] ?? 0);

        if ($size <= 0) {
            $this->setError('File trống hoặc không hợp lệ.');
            return false;
        }

        if ($size > $this->maxBytes) {
            $limitMb = round($this->maxBytes / 1024 / 1024, 1);
            $this->setError(sprintf('File vượt quá giới hạn %sMB.', $limitMb));
            return false;
        }

        return true;
    }
}
