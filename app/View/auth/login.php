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
    <title>Đăng nhập | CloudUploader Pro</title>
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <h2>Chào mừng trở lại</h2>
                <p>Đăng nhập để quản lý các tệp tin của bạn</p>
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

            <form class="auth-form" method="post" action="<?= htmlspecialchars($baseUrl) ?>/login">
                <div class="form-group">
                    <label for="username">Tài khoản</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldUsername) ?>" placeholder="Nhập tên đăng nhập" autocomplete="username" required>
                        <span class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                        <span class="input-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <span>Đăng nhập</span>
                </button>
            </form>

            <p class="auth-switch">Chưa có tài khoản? <a href="<?= htmlspecialchars($baseUrl) ?>/register">Đăng ký ngay</a></p>
        </section>
    </main>
</body>
</html>
