<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/Istanbul');

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if ($_ENV['DEBUG'] === 'false') {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Session başlat
App\Core\Session::start();

// Router oluştur ve route'ları yükle
$router = new App\Core\Router();
require_once dirname(__DIR__) . '/config/routes.php';

// Dispatch
$router->dispatch();