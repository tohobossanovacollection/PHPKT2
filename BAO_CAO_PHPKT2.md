# HƯỚNG DẪN BÁO CÁO ĐỒ ÁN PHPKT2
*(Tài liệu dành cho nhóm thuyết trình)*

## 1. TỔNG QUAN KIẾN TRÚC
Dự án được xây dựng theo mô hình **MVC (Model-View-Controller)** hiện đại, kết hợp với các nguyên lý thiết kế phần mềm sạch (**Clean Architecture**).

*   **Ngôn ngữ**: PHP 8.x (Sử dụng `strict_types=1` để đảm bảo tính chặt chẽ của dữ liệu).
*   **Database**: Sử dụng file JSON (`storage/`) thay vì SQL để tăng tính cơ động và đơn giản cho bài tập.
*   **Quản lý đối tượng**: Sử dụng **DI Container** (Dependency Injection) để quản lý vòng đời của các class.

---

## 2. CẤU TRÚC THƯ MỤC
*   `public/`: Thư mục công khai. Chứa `index.php` (điểm bắt đầu) và các file CSS/Upload.
*   `app/Controller/`: Tiếp nhận và điều hướng yêu cầu từ người dùng.
*   `app/Model/`: Trái tim của ứng dụng:
    *   `Repositories/`: Xử lý đọc/ghi file JSON.
    *   `Services/`: Các nghiệp vụ chuyên sâu (Upload file, Xử lý ảnh).
    *   `Core/`: Các thành phần cốt lõi (Autoloader, BaseController, Container).
*   `app/View/`: Chứa các giao diện HTML/PHP.
*   `storage/`: Nơi lưu trữ dữ liệu (Database file JSON).

---

## 3. LUỒNG XỬ LÝ (REQUEST FLOW)
1.  **Router**: `index.php` nhận URL (ví dụ `/upload`).
2.  **Controller**: `UploadController` kiểm tra quyền (đăng nhập chưa?).
3.  **Service**: `FileUploader` kiểm tra file (size, loại file) và lưu vào ổ đĩa.
4.  **Repository**: `UploadRepository` lưu thông tin file vào `uploads.json`.
5.  **View**: Trả về giao diện danh sách file cho người dùng.

---

## 4. CÁC ĐIỂM NHẤN KỸ THUẬT (ĐỂ ĂN ĐIỂM)

### A. Bảo mật (Security)
*   **Chống Path Traversal**: Sử dụng hàm `basename()` trong `UploadController.php` (dòng 156) để hacker không thể xóa file hệ thống bằng đường dẫn giả mạo.
*   **Kiểm tra quyền sở hữu**: Hàm `deleteForOwner()` đảm bảo người dùng chỉ xóa được file do chính họ upload.
*   **Tên file an toàn**: Sử dụng `uniqid()` kết hợp chuỗi ngẫu nhiên để tránh lộ tên file gốc và trùng lặp.
*   **Hash mật khẩu**: Mật khẩu người dùng được mã hóa bằng `password_hash()` (BCRYPT), không lưu văn bản thuần.

### B. Kỹ thuật nâng cao
*   **Chế độ nghiêm ngặt (strict_types=1)**: Tất cả các file đều có dòng này để bắt buộc kiểm tra kiểu dữ liệu khắt khe, giúp loại bỏ các lỗi logic về kiểu dữ liệu ngay từ đầu.
*   **Model Entity (Lớp thực thể)**: Sử dụng các class như `User`, `UploadedFile` thay vì dùng mảng thô (Array). Điều này giúp code an toàn hơn (Type Safety) và chuyên nghiệp hơn.
*   **Tự động xử lý ảnh**: Hệ thống tự động Resize và Nén ảnh (Compress) ngay khi upload để tiết kiệm dung lượng server mà vẫn giữ được chất lượng (xem trong `FileUploader.php`).
*   **Autoloader**: Tự động nạp Class theo chuẩn PSR-4, không dùng `include/require` thủ công.
*   **DI Container**: Quản lý sự phụ thuộc giữa các Class, giúp code dễ mở rộng và bảo trì.
*   **Validator**: Các lớp kiểm tra dữ liệu riêng biệt giúp tách biệt logic kiểm tra và logic xử lý.

#### *Phụ lục 1: Chi tiết về lớp Container.php (Trái tim của DI)*
Trong dự án PHPKT2, bạn hãy mở file `public/index.php` (từ dòng 37). Bạn sẽ thấy biến `$container` được khởi tạo từ lớp này. Lớp `Container` thực hiện 3 nhiệm vụ cốt lõi:
1.  **bind($id, $factory)**: Lưu trữ "công thức" tạo đối tượng (dưới dạng một hàm Closure). Container không tạo đối tượng ngay lập tức mà đợi khi có yêu cầu.
2.  **set($id, $instance)**: Lưu trữ một giá trị hoặc đối tượng có sẵn (như các biến cấu hình).
3.  **get($id)**: Đây là hàm thông minh nhất. Nó kiểm tra xem đối tượng đã được tạo chưa:
    *   Nếu rồi: Trả về kết quả ngay (giúp tiết kiệm bộ nhớ, chỉ tạo 1 lần duy nhất - Singleton).
    *   Nếu chưa: Nó sẽ sử dụng "công thức" đã `bind` trước đó để tạo ra đối tượng, lưu lại và trả về.

*   **Ví dụ**: Giống như một **"Quản kho"**. Thay vì mỗi người thợ (Controller) phải tự đi mua công cụ (Repository, Uploader), người thợ chỉ cần yêu cầu "Tôi cần công cụ X". Quản kho sẽ tự động chuẩn bị và đưa tận tay cho người thợ qua hàm `get()`.
*   **Lợi ích**: 
    1.  **Dễ bảo trì**: Thay đổi cấu hình chỉ cần sửa 1 chỗ tại `index.php`.
    2.  **Liên kết lỏng lẻo (Loose Coupling)**: Các Class không cần biết cách tạo ra nhau, giúp code sạch và chuyên nghiệp hơn.
    3.  **Tự động hóa**: Container tự động "tiêm" (inject) các phụ thuộc vào hàm `__construct` của Controller.

#### *Phụ lục 2: Tại sao cần hàm normalizeFiles?*
Đây là một kỹ thuật xử lý dữ liệu thực tế khi làm việc với PHP:
*   **Vấn đề**: Khi bạn chọn nhiều file để upload, PHP trả về mảng `$_FILES` theo kiểu "gom nhóm thuộc tính" (Z-structure). Ví dụ: tất cả tên file nằm trong một mảng, tất cả kích thước nằm trong mảng khác. Điều này khiến việc dùng vòng lặp `foreach` để xử lý từng file cực kỳ khó khăn.
*   **Giải pháp**: Hàm `normalizeFiles` trong `UploadController` đóng vai trò "xoay" dữ liệu. Nó chuyển đổi cấu trúc phức tạp của PHP thành một danh sách các file đơn giản (Row-structure).
*   **Kết quả**: Giúp code ở hàm `store()` cực kỳ ngắn gọn, chỉ cần `foreach` qua danh sách đã chuẩn hóa là có thể xử lý từng file một cách độc lập. Đây là cách tiếp cận của các Framework lớn như Laravel hay Symfony.

#### *Phụ lục 3: Vai trò của BaseController*
`BaseController` là một "Lớp cha" (Parent Class) chứa các công cụ dùng chung cho toàn bộ ứng dụng:
*   **render()**: Tự động nạp file giao diện và chuyển dữ liệu sang View.
*   **redirect()**: Hỗ trợ chuyển hướng trang nhanh và an toàn.
*   **requireAuth()**: Một "chốt bảo vệ" giúp kiểm tra đăng nhập. Nếu chưa đăng nhập, nó sẽ tự động chuyển hướng người dùng về trang Login.
*   **Nguyên lý DRY**: Việc tập trung các hàm này tại BaseController giúp tránh lặp lại code, giúp các Controller khác (con) ngắn gọn và dễ bảo trì hơn.

#### *Phụ lục 4: Tại sao cần strict_types=1?*
Đây là lệnh bắt buộc PHP phải kiểm tra kiểu dữ liệu một cách nghiêm túc:
*   **Không có strict**: Nếu hàm cần số nguyên mà bạn đưa chuỗi `"10"`, PHP tự ý đổi thành số `10`. Điều này dễ gây ra các lỗi logic ngầm.
*   **Có strict**: Nếu đưa sai kiểu dữ liệu (ví dụ đưa chuỗi vào chỗ cần số), PHP sẽ báo lỗi ngay lập tức.
*   **Lợi ích**: Giúp phát hiện lỗi cực sớm ngay khi viết code, đảm bảo dữ liệu trong hệ thống luôn chính xác và minh bạch. Đây là tiêu chuẩn của các Framework chuyên nghiệp.

#### *Phụ lục 5: Cơ chế của Autoloader (Tự động nạp Class)*
File `app/Model/Core/Autoloader.php` đóng vai trò là "Người tìm đường" cho hệ thống:
1.  **Vấn đề**: Một dự án lớn có hàng trăm file Class. Nếu dùng `include` hay `require` thủ công sẽ rất dễ nhầm lẫn và khó quản lý.
2.  **Giải pháp (PSR-4)**: Autoloader sử dụng cơ chế `spl_autoload_register`. Khi bạn gọi một Class (ví dụ: `use App\Controllers\UploadController`), PHP sẽ hỏi Autoloader: "File này nằm ở đâu?".
3.  **Cách hoạt động**: 
    *   Nó sẽ cắt bỏ tiền tố `App\`.
    *   Sử dụng bảng ánh xạ `NAMESPACE_ROOT_MAP` để biết rằng sub-namespace `Controllers` tương ứng với thư mục `app/Controller/`.
    *   Tự động ghép nối để tìm ra đường dẫn thực tế: `app/Controller/UploadController.php` và `require` nó vào.
4.  **Lợi ích**: Giúp cấu trúc dự án cực kỳ gọn gàng, bạn chỉ cần tạo file đúng chỗ là hệ thống tự nhận diện, không bao giờ phải lo lắng về việc thiếu `require`.

---

## 5. HƯỚNG DẪN THUYẾT TRÌNH (SCRIPT)
1.  **Bước 1**: Giới thiệu giao diện web (Đăng ký, Đăng nhập).
2.  **Bước 2**: Thực hiện Upload cùng lúc nhiều file (Multiple Upload) để thấy sự tiện lợi.
3.  **Bước 3**: Mở code file `public/index.php` (dòng 3) để chỉ cho cô thấy dòng **strict_types=1** và giải thích ý nghĩa của nó.
4.  **Bước 4**: Mở tiếp dòng 37 của file này để giải thích về **DI Container**.
5.  **Bước 5**: Mở `app/Model/Core/BaseController.php` (dòng 44) để nói về **requireAuth()**.
6.  **Bước 6**: Mở `app/Model/Core/Autoloader.php` để giải thích về **cơ chế tự động nạp Class (PSR-4)**.
7.  **Bước 7**: Mở `app/Controller/UploadController.php` (dòng 124) để giải thích về **hàm chuẩn hóa file**.
8.  **Bước 8**: Giải thích về bảo mật `basename()` và kiểm tra quyền sở hữu.
9.  **Bước 9**: Kết thúc và nhấn mạnh tính chuyên nghiệp của hệ thống.

---
*Chúc nhóm chúng ta báo cáo thành công rực rỡ!*
