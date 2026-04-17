# PHP MVC - Upload File (OOP)

Project mẫu cho bài tập **Xử lý Upload File** theo yêu cầu OOP + MVC.

## Kiến trúc

Thư mục `app` đã được gom lại còn đúng 3 thư mục lớn theo MVC:

- `app/Controller`: các controller xử lý request/response
- `app/Model`: toàn bộ lớp phía model (entities, services, validators, repository, core)
- `app/View`: giao diện hiển thị

Chi tiết hiện tại:

- `app/Controller/UploadController.php`
- `app/Model/Core`: `Autoloader`, `Container`, `BaseController`
- `app/Model/Models`: model dữ liệu `UploadedFile`
- `app/Model/Repositories`: `UploadRepository`
- `app/Model/Services`: `FileUploader`, `ImageProcessor`, `FileTypeMap`
- `app/Model/Validators`: các validator theo chuỗi
- `app/Model/Interfaces`: `FileValidatorInterface`
- `app/Model/Traits`: trait tiện ích xử lý tên file
- `app/View/upload/form.php`: giao diện upload + preview
- `public`: front controller + thư mục file upload public

> Lưu ý: namespace cũ vẫn được giữ nguyên, `Autoloader` đã map sang cấu trúc thư mục mới nên dự án vẫn chạy bình thường.

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

3. Truy cập: `http://localhost:8000`

Nếu chạy qua Apache/XAMPP document root, truy cập theo đường dẫn project của bạn, ví dụ:

- `http://localhost/PHPKT2/public`

## Cấu hình nhanh

Sửa file `config/upload.php`:

- `max_size`: giới hạn dung lượng (bytes)
- `allowed_extensions_by_category`: extension cho từng nhóm
- `allowed_mime_by_extension`: MIME cho từng extension
- `text_preview_max_length`: độ dài preview txt
