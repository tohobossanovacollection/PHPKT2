<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\FileValidatorInterface;
use App\Traits\FileNameSanitizerTrait;
use RuntimeException;

final class FileUploader
{
    use FileNameSanitizerTrait;

    /** @var array<int, FileValidatorInterface> */
    private array $validators = [];

    /**
     * @param iterable<FileValidatorInterface> $validators
     * @param array<string, mixed> $imageConfig
     */
    public function __construct(
        iterable $validators = [],
        private ?ImageProcessor $imageProcessor = null,
        private array $imageConfig = []
    ) {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    public function addValidator(FileValidatorInterface $v): self
    {
        $this->validators[] = $v;
        return $this;
    }

    /**
     * @param array<string, mixed> $file
     */
    public function upload(array $file, string $destination): string
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($file)) {
                throw new RuntimeException($validator->getError());
            }
        }

        if (!is_dir($destination) && !mkdir($destination, 0775, true) && !is_dir($destination)) {
            throw new RuntimeException('Không thể tạo thư mục upload.');
        }

        $originalName = (string) ($file['name'] ?? 'file');
        $storedName = $this->uniqueFileName($originalName);
        $targetPath = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $storedName;

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if (!move_uploaded_file($tmpName, $targetPath)) {
            throw new RuntimeException('Di chuyển file upload thất bại.');
        }

        $this->postProcessImage($targetPath, $storedName);

        return $storedName;
    }

    private function postProcessImage(string $targetPath, string $storedName): void
    {
        if ($this->imageProcessor === null) {
            return;
        }

        $extension = strtolower((string) pathinfo($storedName, PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
        if (!$isImage) {
            return;
        }

        if (($this->imageConfig['auto_resize'] ?? false) === true) {
            $maxW = (int) ($this->imageConfig['max_width'] ?? 1600);
            $maxH = (int) ($this->imageConfig['max_height'] ?? 1600);
            $this->imageProcessor->resize($targetPath, $maxW, $maxH);
        }

        if (($this->imageConfig['auto_compress'] ?? false) === true) {
            $quality = (int) ($this->imageConfig['jpeg_quality'] ?? 82);
            $this->imageProcessor->compress($targetPath, $quality);
        }
    }
}
