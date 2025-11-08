<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// For cPanel: everything is in public_html, so base path is __DIR__
$basePath = __DIR__;

// Check if vendor exists (required for Laravel)
if (!file_exists($basePath.'/vendor/autoload.php')) {
    http_response_code(500);
    die('
    <h1>Laravel Installation Error</h1>
    <p><strong>Error:</strong> Composer dependencies not found.</p>
    <p><strong>Solution:</strong> Run <code>composer install</code> in the deployment directory.</p>
    <p><strong>Expected path:</strong> ' . htmlspecialchars($basePath.'/vendor/autoload.php') . '</p>
    <p><strong>Current directory:</strong> ' . htmlspecialchars(__DIR__) . '</p>
    ');
}

// Check if bootstrap exists
if (!file_exists($basePath.'/bootstrap/app.php')) {
    http_response_code(500);
    die('
    <h1>Laravel Bootstrap Error</h1>
    <p><strong>Error:</strong> Bootstrap file not found.</p>
    <p><strong>Expected path:</strong> ' . htmlspecialchars($basePath.'/bootstrap/app.php') . '</p>
    <p><strong>Current directory:</strong> ' . htmlspecialchars(__DIR__) . '</p>
    ');
}

// Determine if the application is in maintenance mode...
$maintenance = $basePath.'/storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    require $maintenance;
}

// Register the Composer autoloader...
require $basePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $basePath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
