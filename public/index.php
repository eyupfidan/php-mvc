<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// .env yükle
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Debug moduna göre hata gösterimi
if ($_ENV['DEBUG'] === 'false') {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Uygulamayı başlat ve çalıştır
$app = App\Core\App::getInstance();

$request = $app->request();
$response = $app->response();

echo "<h1>Mini MVC Framework</h1>";
echo "<p><strong>URI:</strong> " . e($request->uri()) . "</p>";
echo "<p><strong>Method:</strong> " . e($request->method()) . "</p>";
echo "<p><strong>APP_NAME:</strong> " . e(env('APP_NAME')) . "</p>";
echo "<p><strong>DEBUG:</strong> " . (env('DEBUG') ? 'true' : 'false') . "</p>";

// Session test
App\Core\Session::set('test', 'Session çalışıyor!');
echo "<p><strong>Session Test:</strong> " . e(App\Core\Session::get('test')) . "</p>";

echo "<hr>";
echo "<p style='color:green;'> Core Sınıfları init</p>";

