<?php

declare(strict_types=1);

namespace App\Traits;

trait FileNameSanitizerTrait
{
    protected function sanitizeFileName(string $name): string
    {
        $safe = preg_replace('/[^A-Za-z0-9._-]/', '_', $name) ?? 'file';
        $safe = trim($safe, '._-');

        return $safe !== '' ? $safe : 'file';
    }

    protected function uniqueFileName(string $originalName): string
    {
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = $this->sanitizeFileName($base);
        $stamp = date('Ymd_His') . '_' . bin2hex(random_bytes(4));

        return $extension !== '' ? sprintf('%s_%s.%s', $base, $stamp, $extension) : sprintf('%s_%s', $base, $stamp);
    }
}
