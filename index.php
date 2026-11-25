<?php

declare(strict_types=1);

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load database connection
require_once __DIR__ . '/config/database.php';

// Load routes
require_once __DIR__ . '/routers/web.php';

// Get requested URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove project folder from URL
$basePath = '/movies-booking';

if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Treat /index.php as the homepage
if ($uri === '/index.php') {
    $uri = '/';
}

// Handle empty URI
$uri = $uri === '' ? '/' : $uri;

// Dispatch request
$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
