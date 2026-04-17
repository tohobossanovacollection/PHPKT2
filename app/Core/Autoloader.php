<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    public static function register(string $basePath): void
    {
        spl_autoload_register(static function (string $className) use ($basePath): void {
            $prefix = 'App\\';

            if (strncmp($className, $prefix, strlen($prefix)) !== 0) {
                return;
            }

            $relative = substr($className, strlen($prefix));
            $file = rtrim($basePath, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . str_replace('\\', DIRECTORY_SEPARATOR, $relative)
                . '.php';

            if (is_file($file)) {
                require_once $file;
            }
        });
    }
}
