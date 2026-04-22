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
    <title>Premium File Uploader | OOP MVC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl) ?>/css/style.css">
</head>
<body>
    <nav class="navbar">
        <h1>CloudUploader Pro</h1>
    </nav>

    <main class="container">
        <section class="upload-section">
            <div class="section-header">
                <h2>Tải lên tài liệu của bạn</h2>
                <p>Kéo thả hoặc chọn file để bắt đầu upload lên hệ thống bảo mật của chúng tôi.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?= (string)$error ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?= htmlspecialchars((string)$success) ?></span>
                </div>
            <?php endif; ?>

            <form class="form-group" method="post" action="<?= htmlspecialchars($baseUrl) ?>/upload" enctype="multipart/form-data">
                <div class="drop-zone" id="drop_zone">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <div id="drop_zone_text">
                        <p><strong>Chọn file</strong> hoặc kéo thả vào đây</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Hỗ trợ ảnh, PDF, Word và Text</p>
                    </div>
                    <input type="file" name="upload_file[]" id="file_input" multiple required>
                </div>

                <div id="file_list_display" style="display: none; margin-top: -0.5rem; margin-bottom: 1.5rem; font-size: 0.875rem; text-align: left; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); position: relative; z-index: 10;">
                    <strong style="display: block; margin-bottom: 10px; color: var(--primary); border-bottom: 1px solid #f1f5f9; padding-bottom: 5px;">Các file chuẩn bị tải lên:</strong>
                    <ul id="selected_files_names" style="margin: 0; padding: 0; list-style: none; color: var(--text-main);"></ul>
                </div>

                <button type="submit" class="btn">
                    <span>Tải lên ngay</span>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>

            <div class="limits">
                <div class="limit-item">
                    <span>Dung lượng tối đa:</span>
                    <span class="limit-badge"><?= htmlspecialchars((string)$maxSizeMb) ?>MB</span>
                </div>
                <?php foreach ($allowedByCategory as $cat => $exts): ?>
                    <div class="limit-item">
                        <span><?= htmlspecialchars(ucfirst($cat)) ?>:</span>
                        <span class="limit-badge"><?= htmlspecialchars(implode(', ', $exts)) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div id="existing_files_section">
            <h2>File của bạn</h2>

            <?php if ($files === []): ?>
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                    <p>Chưa có file nào được tải lên.</p>
                </div>
            <?php else: ?>
                <div class="files-grid">
                    <?php foreach (array_reverse($files) as $item): ?>
                        <div class="file-card">
                            <div class="preview-container">
                                <?php if ($item->category === 'image'): ?>
                                    <img src="<?= htmlspecialchars($baseUrl) ?>/uploads/<?= rawurlencode($item->storedName) ?>" class="preview-img" alt="Preview">
                                <?php elseif ($item->extension === 'txt'): ?>
                                    <div class="preview-text"><?= htmlspecialchars($textPreviews[$item->storedName] ?? 'No preview') ?></div>
                                <?php elseif ($item->extension === 'pdf'): ?>
                                    <svg class="preview-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h1m0 4h3m-3 4h3"></path></svg>
                                <?php else: ?>
                                    <svg class="preview-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <?php endif; ?>
                            </div>
                            <div class="file-details">
                                <span class="file-category"><?= htmlspecialchars($item->category) ?> • <?= htmlspecialchars(strtoupper($item->extension)) ?></span>
                                <h3 class="file-name" title="<?= htmlspecialchars($item->originalName) ?>"><?= htmlspecialchars($item->originalName) ?></h3>
                                <div class="file-meta">
                                    <span>Kích thước: <?= htmlspecialchars(number_format($item->size / 1024, 1)) ?> KB</span>
                                    <span>Ngày tải: <?= htmlspecialchars($item->uploadedAt) ?></span>
                                </div>
                            </div>
                            <div class="card-actions" style="gap: 1rem;">
                                <a href="javascript:void(0)" 
                                   class="action-link" 
                                   onclick="openViewer('<?= htmlspecialchars($baseUrl) ?>/uploads/<?= rawurlencode($item->storedName) ?>', '<?= $item->category ?>', '<?= $item->extension ?>', '<?= htmlspecialchars($item->originalName) ?>')">
                                    <span>Xem file</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="javascript:void(0)" 
                                   class="action-link" 
                                   style="color: var(--error);"
                                   onclick="confirmDelete('<?= htmlspecialchars($baseUrl) ?>/delete?name=<?= urlencode($item->storedName) ?>', '<?= htmlspecialchars($item->originalName) ?>')">
                                    <span>Xóa</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <!-- Modal Xem Chi Tiết File -->
    <div id="viewer_modal" class="modal-overlay">
        <div class="modal-content" style="max-width: 900px; width: 95%; height: 90vh; display: flex; flex-direction: column; padding: 0; overflow: hidden;">
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fff;">
                <div>
                    <h3 id="viewer_title" style="margin: 0; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 600px;">Xem file</h3>
                    <div id="viewer_subtitle" style="font-size: 0.75rem; color: var(--text-muted);">Đang hiển thị bản xem trước</div>
                </div>
                <button onclick="closeViewer()" class="btn-cancel" style="padding: 0.5rem; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</button>
            </div>
            <div id="viewer_body" style="flex-grow: 1; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: auto; padding: 1rem;">
                <!-- Content will be injected here -->
            </div>
            <div style="padding: 1rem; border-top: 1px solid var(--border); background: #fff; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a id="download_btn" href="#" download class="btn-cancel" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Tải về máy
                </a>
                <button onclick="closeViewer()" class="btn" style="width: auto; padding: 0.5rem 1.5rem;">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Modal Xóa File -->
    <div id="delete_modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-icon-container">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem;">Xác nhận xóa file?</h3>
            <p id="delete_modal_text" style="color: var(--text-muted); font-size: 0.9375rem; margin-bottom: 2rem;">Bạn có chắc chắn muốn xóa file này? Hành động này không thể hoàn tác.</p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <button onclick="closeDeleteModal()" class="btn-cancel">Hủy bỏ</button>
                <a id="confirm_delete_btn" href="#" class="btn-delete">Xóa ngay</a>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop_zone');
        const fileInput = document.getElementById('file_input');
        const fileListDisplay = document.getElementById('file_list_display');
        const selectedFilesNames = document.getElementById('selected_files_names');
        const dropZoneText = document.getElementById('drop_zone_text');

        // Mảng lưu trữ danh sách file đã chọn để tích lũy
        let selectedFiles = [];

        function updateFileList() {
            const newFiles = Array.from(fileInput.files);
            
            // Tích lũy file mới, tránh trùng lặp nếu chọn lại cùng 1 file
            newFiles.forEach(file => {
                const isDuplicate = selectedFiles.some(f => 
                    f.name === file.name && 
                    f.size === file.size && 
                    f.lastModified === file.lastModified
                );
                if (!isDuplicate) {
                    selectedFiles.push(file);
                }
            });

            renderFileList();
        }

        function renderFileList() {
            // Cập nhật lại fileInput.files từ mảng tích lũy để form submit đúng
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;

            if (selectedFiles.length > 0) {
                selectedFilesNames.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.className = 'selected-file-item';
                    
                    const leftSide = document.createElement('div');
                    leftSide.className = 'selected-file-left';

                    const preview = document.createElement('div');
                    preview.className = 'selected-preview';

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        img.onload = () => URL.revokeObjectURL(img.src);
                        preview.appendChild(img);
                    } else if (file.type.startsWith('audio/')) {
                        preview.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>';
                    } else if (file.type.startsWith('video/')) {
                        preview.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
                    } else {
                        preview.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                    }
                    
                    const info = document.createElement('div');
                    const nameSpan = document.createElement('div');
                    nameSpan.textContent = file.name;
                    nameSpan.className = 'selected-info-name';
                    
                    const sizeSpan = document.createElement('div');
                    sizeSpan.textContent = `${(file.size / 1024).toFixed(1)} KB`;
                    sizeSpan.className = 'selected-info-size';
                    
                    info.appendChild(nameSpan);
                    info.appendChild(sizeSpan);
                    
                    leftSide.appendChild(preview);
                    leftSide.appendChild(info);
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.className = 'remove-file-btn';
                    removeBtn.title = 'Xóa file này';
                    
                    removeBtn.onclick = (e) => {
                        e.preventDefault();
                        removeFile(index);
                    };

                    li.appendChild(leftSide);
                    li.appendChild(removeBtn);
                    selectedFilesNames.appendChild(li);
                });
                fileListDisplay.style.display = 'block';
                dropZoneText.style.display = 'none';
            } else {
                fileListDisplay.style.display = 'none';
                dropZoneText.style.display = 'block';
                fileInput.value = '';
            }
        }

        function removeFile(indexToRemove) {
            selectedFiles.splice(indexToRemove, 1);
            renderFileList();
        }

        fileInput.addEventListener('change', updateFileList);


        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const newFiles = dt.files;
            fileInput.files = newFiles;
            updateFileList();
        }

        // Deletion Modal Logic
        const deleteModal = document.getElementById('delete_modal');
        const confirmDeleteBtn = document.getElementById('confirm_delete_btn');
        const deleteModalText = document.getElementById('delete_modal_text');

        function confirmDelete(url, fileName) {
            deleteModalText.textContent = `Bạn có chắc chắn muốn xóa file "${fileName}" không? Hành động này không thể hoàn tác.`;
            confirmDeleteBtn.href = url;
            deleteModal.style.display = 'flex';
        }

        function closeDeleteModal() {
            deleteModal.style.display = 'none';
        }

        // Viewer Modal Logic
        const viewerModal = document.getElementById('viewer_modal');
        const viewerBody = document.getElementById('viewer_body');
        const viewerTitle = document.getElementById('viewer_title');
        const downloadBtn = document.getElementById('download_btn');

        async function openViewer(url, category, ext, name) {
            viewerTitle.textContent = name;
            downloadBtn.href = url;
            viewerBody.innerHTML = '<div style="color: var(--text-muted);">Đang tải...</div>';
            viewerModal.style.display = 'flex';

            try {
                if (category === 'image') {
                    viewerBody.innerHTML = `<img src="${url}" style="max-width: 100%; max-height: 100%; object-fit: contain; box-shadow: var(--shadow);">`;
                } else if (category === 'audio') {
                    viewerBody.innerHTML = `
                        <div style="text-align: center; background: white; padding: 3rem; border-radius: 12px; box-shadow: var(--shadow); width: 100%; max-width: 500px;">
                            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--primary); margin-bottom: 1.5rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                            <audio controls src="${url}" style="width: 100%;"></audio>
                            <p style="margin-top: 1rem; color: var(--text-muted); font-size: 0.875rem;">Đang phát âm thanh</p>
                        </div>
                    `;
                } else if (category === 'video') {
                    viewerBody.innerHTML = `<video controls src="${url}" style="max-width: 100%; max-height: 100%; box-shadow: var(--shadow); border-radius: 8px;"></video>`;
                } else if (ext === 'pdf') {
                    viewerBody.innerHTML = `<iframe src="${url}" style="width: 100%; height: 100%; border: none; background: white;"></iframe>`;
                } else if (ext === 'txt') {
                    const response = await fetch(url);
                    const text = await response.text();
                    viewerBody.innerHTML = `<pre style="width: 100%; height: 100%; background: white; padding: 2rem; margin: 0; overflow: auto; text-align: left; font-size: 0.9rem; line-height: 1.5; border-radius: 8px; box-shadow: var(--shadow-sm);">${escapeHtml(text)}</pre>`;
                } else {
                    viewerBody.innerHTML = `
                        <div style="text-align: center; background: white; padding: 3rem; border-radius: 12px; box-shadow: var(--shadow);">
                            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-muted); margin-bottom: 1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <p>Không hỗ trợ xem trực tiếp định dạng <strong>.${ext.toUpperCase()}</strong></p>
                            <a href="${url}" download class="btn" style="text-decoration: none; display: inline-flex; margin-top: 1rem;">Tải xuống để xem</a>
                        </div>
                    `;
                }
            } catch (error) {
                viewerBody.innerHTML = '<div style="color: var(--error);">Không thể nạp nội dung file.</div>';
            }
        }

        function closeViewer() {
            viewerModal.style.display = 'none';
            viewerBody.innerHTML = '';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        window.onclick = function(event) {
            if (event.target == deleteModal) closeDeleteModal();
            if (event.target == viewerModal) closeViewer();
        }
    </script>
</body>
</html>
