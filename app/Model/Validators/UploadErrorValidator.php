<?php

declare(strict_types=1);

namespace App\Validators;

final class UploadErrorValidator extends AbstractFileValidator
{
    public function validate(array $file): bool
    {
        $errorCode = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($errorCode === UPLOAD_ERR_OK) {
            return true;
        }

        $message = match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File vượt quá dung lượng cho phép của hệ thống.',
            UPLOAD_ERR_PARTIAL => 'File chỉ upload một phần.',
            UPLOAD_ERR_NO_FILE => 'Bạn chưa chọn file để upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm trên server.',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file lên ổ đĩa.',
            UPLOAD_ERR_EXTENSION => 'Upload bị chặn bởi extension PHP.',
            default => 'Upload thất bại do lỗi không xác định.',
        };

        $this->setError($message);
        return false;
    }
}
