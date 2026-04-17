<?php

declare(strict_types=1);

namespace App\Models;

final class UploadedFile
{
    public string $originalName;
    public string $storedName;
    public string $mimeType;
    public string $extension;
    public int $size;
    public string $category;
    public string $uploadedAt;

    public function __construct(
        string $originalName,
        string $storedName,
        string $mimeType,
        string $extension,
        int $size,
        string $category,
        string $uploadedAt
    ) {
        $this->originalName = $originalName;
        $this->storedName = $storedName;
        $this->mimeType = $mimeType;
        $this->extension = $extension;
        $this->size = $size;
        $this->category = $category;
        $this->uploadedAt = $uploadedAt;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['originalName'] ?? ''),
            (string) ($data['storedName'] ?? ''),
            (string) ($data['mimeType'] ?? ''),
            (string) ($data['extension'] ?? ''),
            (int) ($data['size'] ?? 0),
            (string) ($data['category'] ?? 'unknown'),
            (string) ($data['uploadedAt'] ?? '')
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'originalName' => $this->originalName,
            'storedName' => $this->storedName,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'size' => $this->size,
            'category' => $this->category,
            'uploadedAt' => $this->uploadedAt,
        ];
    }
}
