<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    /** @var array<string, string> */
    private const NAMESPACE_ROOT_MAP = [
        'Controllers' => 'Controller',
        'Core' => 'Model/Core',
        'Interfaces' => 'Model/Interfaces',
        'Models' => 'Model/Models',
        'Repositories' => 'Model/Repositories',
        'Services' => 'Model/Services',
        'Traits' => 'Model/Traits',
        'Validators' => 'Model/Validators',
    ];

    public static function register(string $basePath): void
    {
        spl_autoload_register(static function (string $className) use ($basePath): void {
            $prefix = 'App\\';

            if (strncmp($className, $prefix, strlen($prefix)) !== 0) {
                return;
            }

            $relative = substr($className, strlen($prefix));
            $parts = explode('\\', $relative);
            $root = $parts[0] ?? '';

            if (isset(self::NAMESPACE_ROOT_MAP[$root])) {
                $parts[0] = self::NAMESPACE_ROOT_MAP[$root];
            }

            $file = rtrim($basePath, DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . str_replace(['\\', '/'], DIRECTORY_SEPARATOR, implode('\\', $parts))
                . '.php';

            if (is_file($file)) {
                require_once $file;
            }
        });
    }
}
