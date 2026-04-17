<?php

declare(strict_types=1);

/** @var array<int, \App\Models\UploadedFile> $files */
/** @var array<string, string> $textPreviews */
/** @var float $maxSizeMb */
/** @var array<string, array<int, string>> $allowedByCategory */
/** @var string|null $error */
/** @var string|null $success */
/** @var string $baseUrl */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MVC File Upload - OOP</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6fb; margin: 0; }
        .container { max-width: 1000px; margin: 24px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 8px 20px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        .note { color: #4b5563; font-size: 14px; }
        .alert { padding: 10px 12px; border-radius: 8px; margin: 12px 0; }
        .alert.error { background: #fee2e2; color: #991b1b; }
        .alert.success { background: #dcfce7; color: #166534; }
        .upload-box { display: grid; gap: 10px; padding: 14px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fafafa; }
        .btn { background: #2563eb; color: #fff; border: 0; padding: 10px 16px; border-radius: 8px; cursor: pointer; width: fit-content; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-top: 20px; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; background: #fff; }
        .meta { font-size: 12px; color: #6b7280; margin-top: 6px; }
        .preview-image { width: 100%; max-height: 220px; object-fit: contain; border-radius: 8px; background: #f3f4f6; }
        .preview-text { white-space: pre-wrap; background: #111827; color: #e5e7eb; border-radius: 8px; padding: 10px; font-size: 13px; max-height: 190px; overflow: auto; }
        .badge { display: inline-block; font-size: 11px; padding: 3px 7px; border-radius: 99px; background: #e0e7ff; color: #3730a3; margin-bottom: 8px; }
        .file-link { color: #1d4ed8; text-decoration: none; }
        .empty { color: #6b7280; font-style: italic; }
    </style>
</head>
<body>
<main class="container">
    <h1>Upload File theo MVC + OOP</h1>
    <p class="note">Giới hạn dung lượng tối đa: <strong><?= htmlspecialchars((string) $maxSizeMb) ?>MB</strong></p>

    <p class="note">
        Định dạng cho phép:
        <?php foreach ($allowedByCategory as $cat => $exts): ?>
            <strong><?= htmlspecialchars($cat) ?></strong>: <?= htmlspecialchars(implode(', ', $exts)) ?>&nbsp;
        <?php endforeach; ?>
    </p>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form class="upload-box" method="post" action="<?= htmlspecialchars($baseUrl) ?>/upload" enctype="multipart/form-data">
        <label for="upload_file">Chọn ảnh hoặc tài liệu:</label>
        <input id="upload_file" type="file" name="upload_file" required>
        <button class="btn" type="submit">Upload</button>
    </form>

    <h2>Danh sách file đã upload</h2>

    <?php if ($files === []): ?>
        <p class="empty">Chưa có file nào.</p>
    <?php else: ?>
        <section class="grid">
            <?php foreach ($files as $item): ?>
                <article class="card">
                    <span class="badge"><?= htmlspecialchars(strtoupper($item->extension)) ?> • <?= htmlspecialchars($item->category) ?></span>
                    <h3><?= htmlspecialchars($item->originalName) ?></h3>

                    <?php if ($item->category === 'image'): ?>
                        <img class="preview-image" src="<?= htmlspecialchars($baseUrl) ?>/uploads/<?= rawurlencode($item->storedName) ?>" alt="<?= htmlspecialchars($item->originalName) ?>">
                    <?php elseif ($item->extension === 'txt'): ?>
                        <div class="preview-text"><?= htmlspecialchars($textPreviews[$item->storedName] ?? 'Không có preview.') ?></div>
                    <?php else: ?>
                        <p>📄 Không hỗ trợ render trực tiếp loại này.</p>
                        <a class="file-link" href="<?= htmlspecialchars($baseUrl) ?>/uploads/<?= rawurlencode($item->storedName) ?>" target="_blank" rel="noopener noreferrer">Mở / Tải file</a>
                    <?php endif; ?>

                    <p class="meta">
                        MIME: <?= htmlspecialchars($item->mimeType) ?><br>
                        Size: <?= htmlspecialchars(number_format($item->size / 1024, 1)) ?> KB<br>
                        Uploaded: <?= htmlspecialchars($item->uploadedAt) ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
