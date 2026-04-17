# PHP MVC - Upload File (OOP)

Project mẫu cho bài tập **Xử lý Upload File** theo yêu cầu OOP + MVC.

## Kiến trúc

- `app/Controllers`: xử lý request/response
- `app/Services`: nghiệp vụ (`FileUploader`, `ImageProcessor`, `FileTypeMap`)
- `app/Validators`: các class validation theo chain
- `app/Interfaces`: `FileValidatorInterface`
- `app/Traits`: trait tiện ích xử lý tên file
- `app/Models`: model dữ liệu `UploadedFile`
- `app/Repositories`: lưu/đọc metadata từ `storage/uploads.json`
- `app/Core`: autoload, DI container, base controller
- `app/Views`: giao diện upload + preview
- `public`: front controller + thư mục file upload public

## OOP đã áp dụng

- Interface: `FileValidatorInterface`
- Abstract class: `AbstractFileValidator`
- Trait: `FileNameSanitizerTrait`
- Dependency Injection: inject validators/services qua constructor
- Design Pattern:
  - **Chain of Responsibility** trong `FileUploader`
  - **Repository Pattern** với `UploadRepository`

## Tính năng

- Upload file tối đa mặc định **5MB** (đổi ở `config/upload.php`)
- Giới hạn định dạng theo nhóm:
  - `image`: jpg, jpeg, png, gif, webp
  - `text`: txt, docx, pdf
- Preview:
  - Ảnh: hiển thị trực tiếp
  - txt: đọc và cắt preview nội dung
  - docx/pdf: hiển thị link mở/tải file
- Tự động resize/compress ảnh (nếu có extension GD)

## Chạy dự án

Yêu cầu: PHP 8.1+.

1. Mở terminal tại thư mục project.
2. Chạy server built-in:

```powershell
php -S localhost:8000 -t public
```

3. Truy cập: `localhost/PHPkt2/public`

## Cấu hình nhanh

Sửa file `config/upload.php`:

- `max_size`: giới hạn dung lượng (bytes)
- `allowed_extensions_by_category`: extension cho từng nhóm
- `allowed_mime_by_extension`: MIME cho từng extension
- `text_preview_max_length`: độ dài preview txt
