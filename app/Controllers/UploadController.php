<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UploadedFile;
use App\Repositories\UploadRepository;
use App\Services\FileTypeMap;
use App\Services\FileUploader;
use RuntimeException;

final class UploadController extends BaseController
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private FileUploader $uploader,
        private FileTypeMap $fileTypeMap,
        private UploadRepository $repository,
        private string $publicUploadDir,
        private array $config,
        private string $baseUrl = ''
    ) {
    }

    public function index(): void
    {
        $files = $this->repository->all();
        $previewMax = (int) ($this->config['text_preview_max_length'] ?? 300);

        $textPreviews = [];
        foreach ($files as $file) {
            if ($file->category === 'text' && $file->extension === 'txt') {
                $textPreviews[$file->storedName] = $this->extractTextPreview($file, $previewMax);
            }
        }

        $this->render('upload/form', [
            'files' => $files,
            'textPreviews' => $textPreviews,
            'maxSizeMb' => round(((int) $this->config['max_size']) / 1024 / 1024, 1),
            'allowedByCategory' => (array) ($this->config['allowed_extensions_by_category'] ?? []),
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'baseUrl' => $this->baseUrl,
        ]);

        unset($_SESSION['error'], $_SESSION['success']);
    }

    public function store(): void
    {
        $file = $_FILES['upload_file'] ?? null;

        if (!is_array($file)) {
            $_SESSION['error'] = 'Dữ liệu upload không hợp lệ.';
            $this->redirect($this->baseUrl . '/');
        }

        try {
            $storedName = $this->uploader->upload($file, $this->publicUploadDir);

            $tmpUploadPath = $this->publicUploadDir . DIRECTORY_SEPARATOR . $storedName;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = $finfo ? (string) finfo_file($finfo, $tmpUploadPath) : 'application/octet-stream';
            if ($finfo) {
                finfo_close($finfo);
            }

            $extension = strtolower((string) pathinfo($storedName, PATHINFO_EXTENSION));
            $category = $this->fileTypeMap->detectCategory($extension);

            $uploaded = new UploadedFile(
                originalName: (string) ($file['name'] ?? $storedName),
                storedName: $storedName,
                mimeType: $mimeType,
                extension: $extension,
                size: (int) filesize($tmpUploadPath),
                category: $category,
                uploadedAt: date('Y-m-d H:i:s')
            );

            $this->repository->save($uploaded);
            $_SESSION['success'] = 'Upload file thành công.';
        } catch (RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect($this->baseUrl . '/');
    }

    private function extractTextPreview(UploadedFile $file, int $maxLength): string
    {
        $path = $this->publicUploadDir . DIRECTORY_SEPARATOR . $file->storedName;
        if (!is_file($path)) {
            return 'Không tìm thấy nội dung preview.';
        }

        $content = file_get_contents($path);
        if ($content === false || $content === '') {
            return 'Không thể đọc nội dung file.';
        }

        $text = mb_convert_encoding($content, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

        if (mb_strlen($text) <= $maxLength) {
            return $text;
        }

        return mb_substr($text, 0, $maxLength) . '...';
    }
}
