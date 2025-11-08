<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine the base path - handle both standard Laravel and cPanel deployments
$basePath = __DIR__;
// For cPanel: if vendor doesn't exist here, check parent directory
if (!file_exists($basePath.'/vendor/autoload.php') && file_exists($basePath.'/../vendor/autoload.php')) {
    $basePath = __DIR__.'/..';
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
