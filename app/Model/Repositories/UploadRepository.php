<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UploadedFile;
use RuntimeException;

final class UploadRepository
{
    public function __construct(private string $jsonPath)
    {
    }

    /**
     * @return array<int, UploadedFile>
     */
    public function all(): array
    {
        if (!is_file($this->jsonPath)) {
            return [];
        }

        $content = file_get_contents($this->jsonPath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        $items = array_map(static fn (array $item): UploadedFile => UploadedFile::fromArray($item), $decoded);

        usort($items, static fn (UploadedFile $a, UploadedFile $b): int => strcmp($b->uploadedAt, $a->uploadedAt));

        return $items;
    }

    public function save(UploadedFile $uploadedFile): void
    {
        $list = $this->all();
        array_unshift($list, $uploadedFile);

        $serialized = array_map(static fn (UploadedFile $item): array => $item->toArray(), $list);
        $json = json_encode($serialized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Không thể encode dữ liệu file upload.');
        }

        if (file_put_contents($this->jsonPath, $json) === false) {
            throw new RuntimeException('Không thể lưu metadata file upload.');
        }
    }
}
