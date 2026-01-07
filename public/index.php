<?php

declare(strict_types=1);



error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Europe/Istanbul');

// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if ($_ENV['DEBUG'] === 'false') {
    error_reporting(0);
    ini_set('display_errors', '0');
}

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$basePath = dirname($_SERVER['SCRIPT_NAME']);

if ($basePath !== '/' && $basePath !== '\\') {
    $uri = substr($uri, strlen($basePath));
}

if (isset($_SERVER['PATH_INFO'])) {
    $uri = $_SERVER['PATH_INFO'];
}

$uri = parse_url($uri, PHP_URL_PATH);
$uri = '/' . trim($uri ?? '', '/');

$method = $_SERVER['REQUEST_METHOD'];

// Method spoofing
if ($method === 'POST' && isset($_POST['_method'])) {
    $spoofed = strtoupper($_POST['_method']);
    if (in_array($spoofed, ['PUT', 'PATCH', 'DELETE'])) {
        $method = $spoofed;
    }
}

echo "<h1>Mini MVC Framework</h1>";
echo "<p><strong>URI:</strong> " . htmlspecialchars($uri) . "</p>";
echo "<p><strong>Method:</strong> " . htmlspecialchars($method) . "</p>";
echo "<p><strong>APP_NAME:</strong> " . htmlspecialchars($_ENV['APP_NAME']) . "</p>";
echo "<p><strong>DEBUG:</strong> " . htmlspecialchars($_ENV['DEBUG']) . "</p>";
echo "<hr>";
echo "<p style='color:green;'>✅ Adım 1 başarılı! Temel altyapı çalışıyor.</p>";