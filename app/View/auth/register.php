<?php

declare(strict_types=1);

/** @var string $baseUrl */
/** @var string|null $error */
/** @var string|null $success */
/** @var string $oldUsername */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký | CloudUploader Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/css/style.css?v=<?= time() ?>">
</head>
<body class="auth-body">
    <div class="auth-blob blob-1"></div>
    <div class="auth-blob blob-2"></div>

    <nav class="navbar">
        <div class="navbar-content">
            <h1>CloudUploader Pro</h1>
        </div>
    </nav>

    <main class="auth-container">
        <section class="auth-card">
            <div class="auth-header">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h2>Tạo tài khoản mới</h2>
                <p>Khám phá các tính năng lưu trữ đám mây mạnh mẽ</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?= htmlspecialchars((string) $error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?= htmlspecialchars((string) $success) ?></span>
                </div>
            <?php endif; ?>

            <form class="auth-form" method="post" action="<?= htmlspecialchars($baseUrl) ?>/register">
                <div class="form-group">
                    <label for="username">Tài khoản</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldUsername) ?>" placeholder="Tên đăng nhập (4-32 ký tự)" autocomplete="username" minlength="4" maxlength="32" required>
                        <span class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" autocomplete="new-password" minlength="6" required>
                        <span class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" autocomplete="new-password" minlength="6" required>
                        <span class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <span>Đăng ký</span>
                </button>
            </form>

            <p class="auth-switch">Đã có tài khoản? <a href="<?= htmlspecialchars($baseUrl) ?>/login">Đăng nhập ngay</a></p>
        </section>
    </main>
</body>
</html>
