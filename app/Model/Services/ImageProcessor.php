<?php

declare(strict_types=1);

namespace App\Services;

final class ImageProcessor
{
    public function resize(string $path, int $w, int $h): string
    {
        if (!extension_loaded('gd')) {
            return $path;
        }

        [$width, $height, $type] = @getimagesize($path) ?: [0, 0, 0];
        if ($width <= 0 || $height <= 0) {
            return $path;
        }

        if ($width <= $w && $height <= $h) {
            return $path;
        }

        $ratio = min($w / $width, $h / $height);
        $newWidth = max(1, (int) floor($width * $ratio));
        $newHeight = max(1, (int) floor($height * $ratio));

        $src = $this->createImageResource($path, $type);
        if ($src === null) {
            return $path;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ($dst === false) {
            imagedestroy($src);
            return $path;
        }

        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $this->saveImageResource($dst, $path, $type, 85);

        imagedestroy($src);
        imagedestroy($dst);

        return $path;
    }

    public function compress(string $path, int $quality): string
    {
        if (!extension_loaded('gd')) {
            return $path;
        }

        [$width, $height, $type] = @getimagesize($path) ?: [0, 0, 0];
        if ($width <= 0 || $height <= 0) {
            return $path;
        }

        $src = $this->createImageResource($path, $type);
        if ($src === null) {
            return $path;
        }

        $saved = $this->saveImageResource($src, $path, $type, $quality);
        imagedestroy($src);

        return $saved ? $path : $path;
    }

    private function createImageResource(string $path, int $type): mixed
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };
    }

    private function saveImageResource(mixed $image, string $path, int $type, int $quality): bool
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagejpeg($image, $path, max(10, min(100, $quality))),
            IMAGETYPE_PNG => @imagepng($image, $path, (int) round((100 - max(0, min(100, $quality))) / 10)),
            IMAGETYPE_GIF => @imagegif($image, $path),
            IMAGETYPE_WEBP => function_exists('imagewebp')
                ? @imagewebp($image, $path, max(10, min(100, $quality)))
                : false,
            default => false,
        };
    }
}
