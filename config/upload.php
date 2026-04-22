<?php

declare(strict_types=1);

return [
    'max_size' => 50 * 1024 * 1024, // 50MB
    'allowed_extensions_by_category' => [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'text' => ['txt', 'docx', 'pdf'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'video' => ['mp4', 'webm'],
    ],
    'allowed_mime_by_extension' => [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'gif' => ['image/gif'],
        'webp' => ['image/webp'],
        'txt' => ['text/plain', 'application/octet-stream'],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip',
        ],
        'pdf' => ['application/pdf'],
        'mp3' => ['audio/mpeg', 'application/octet-stream'],
        'wav' => ['audio/wav', 'audio/x-wav'],
        'ogg' => ['audio/ogg', 'video/ogg'],
        'mp4' => ['video/mp4'],
        'webm' => ['video/webm'],
    ],
    'text_preview_max_length' => 300,
    'image' => [
        'auto_resize' => true,
        'max_width' => 1600,
        'max_height' => 1600,
        'auto_compress' => true,
        'jpeg_quality' => 82,
    ],
];
