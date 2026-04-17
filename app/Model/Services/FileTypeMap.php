<?php

declare(strict_types=1);

namespace App\Services;

final class FileTypeMap
{
    /**
     * @param array<string, array<int, string>> $allowedByCategory
     */
    public function __construct(private array $allowedByCategory)
    {
    }

    public function detectCategory(string $extension): string
    {
        $ext = strtolower($extension);

        foreach ($this->allowedByCategory as $category => $extensions) {
            if (in_array($ext, $extensions, true)) {
                return $category;
            }
        }

        return 'unknown';
    }

    public function isImage(string $extension): bool
    {
        return $this->detectCategory($extension) === 'image';
    }
}
