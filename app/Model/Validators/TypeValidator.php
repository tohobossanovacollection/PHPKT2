<?php

declare(strict_types=1);

namespace App\Validators;

final class TypeValidator extends AbstractFileValidator
{
    /**
     * @param array<string, array<int, string>> $allowedByCategory
     * @param array<string, array<int, string>> $allowedMimeByExtension
     */
    public function __construct(
        private array $allowedByCategory,
        private array $allowedMimeByExtension
    ) {
    }

    public function validate(array $file): bool
    {
        $name = (string) ($file['name'] ?? '');
        $ext = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));

        if ($ext === '') {
            $this->setError('File không có đuôi mở rộng (extension).');
            return false;
        }

        $allAllowedExtensions = array_merge(...array_values($this->allowedByCategory));
        if (!in_array($ext, $allAllowedExtensions, true)) {
            $this->setError(sprintf('Định dạng .%s chưa được hỗ trợ.', $ext));
            return false;
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            $this->setError('Không tìm thấy file tạm hợp lệ để kiểm tra MIME type.');
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedMime = $this->allowedMimeByExtension[$ext] ?? [];
        if ($allowedMime !== [] && !in_array($mime, $allowedMime, true)) {
            $this->setError(sprintf('MIME type không hợp lệ: %s cho file .%s', $mime, $ext));
            return false;
        }

        return true;
    }
}
