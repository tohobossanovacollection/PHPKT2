<?php

declare(strict_types=1);

use App\Controllers\UploadController;
use App\Core\Autoloader;
use App\Core\Container;
use App\Repositories\UploadRepository;
use App\Services\FileTypeMap;
use App\Services\FileUploader;
use App\Services\ImageProcessor;
use App\Validators\SizeValidator;
use App\Validators\TypeValidator;
use App\Validators\UploadErrorValidator;

session_start();

$basePath = dirname(__DIR__);
require_once $basePath . '/app/Model/Core/Autoloader.php';
Autoloader::register($basePath . '/app');

$config = require $basePath . '/config/upload.php';

$requestUriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$baseUrl = rtrim(str_replace('/index.php', '', $scriptName), '/');

$path = $requestUriPath;
if ($baseUrl !== '' && str_starts_with($requestUriPath, $baseUrl)) {
    $path = substr($requestUriPath, strlen($baseUrl));
}

$path = $path === '' ? '/' : $path;

$container = new Container();
$container->set('config', $config);
$container->set('basePath', $basePath);
$container->set('baseUrl', $baseUrl);

$container->bind('repository', static function (Container $c): UploadRepository {
    return new UploadRepository($c->get('basePath') . '/storage/uploads.json');
});

$container->bind('fileTypeMap', static fn (Container $c): FileTypeMap => new FileTypeMap($c->get('config')['allowed_extensions_by_category']));

$container->bind('uploader', static function (Container $c): FileUploader {
    $cfg = $c->get('config');
    $validators = [
        new UploadErrorValidator(),
        new SizeValidator((int) $cfg['max_size']),
        new TypeValidator(
            $cfg['allowed_extensions_by_category'],
            $cfg['allowed_mime_by_extension']
        ),
    ];

    return new FileUploader($validators, new ImageProcessor(), $cfg['image'] ?? []);
});

$container->bind('uploadDir', static fn (Container $c): string => $c->get('basePath') . '/public/uploads');

$container->bind('controller.upload', static function (Container $c): UploadController {
    return new UploadController(
        $c->get('uploader'),
        $c->get('fileTypeMap'),
        $c->get('repository'),
        $c->get('uploadDir'),
        $c->get('config'),
        $c->get('baseUrl')
    );
});
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

/** @var UploadController $uploadController */
$uploadController = $container->get('controller.upload');

if ($path === '/' && $method === 'GET') {
    $uploadController->index();
    exit;
}

if ($path === '/upload' && $method === 'POST') {
    $uploadController->store();
    exit;
}

if ($path === '/delete' && $method === 'GET') {
    $uploadController->destroy();
    exit;
}

http_response_code(404);
echo '404 - Not Found';
