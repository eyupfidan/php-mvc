<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    private static ?App $instance = null;
    private Request $request;
    private Response $response;
    private Router $router;

    private function __construct()
    {
        // Session başlat
        Session::start();

        // Core nesneleri oluştur
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * Singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Request nesnesini al
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Response nesnesini al
     */
    public function response(): Response
    {
        return $this->response;
    }

    /**
     * Router nesnesini al/ayarla
     */
    public function router(): Router
    {
        if (!isset($this->router)) {
            $this->router = new Router($this->request, $this->response);
        }
        return $this->router;
    }

    /**
     * Uygulamayı çalıştır
     */
    public function run(): void
    {
        try {
            // Route'ları yükle
            $routesFile = dirname(__DIR__, 2) . '/config/routes.php';
            if (file_exists($routesFile)) {
                $router = $this->router();
                require $routesFile;
            }

            // İsteği işle
            $this->router()->dispatch();

        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * Hata yönetimi
     */
    private function handleException(\Throwable $e): void
    {
        if (env('DEBUG', false)) {
            echo '<h1>Error</h1>';
            echo '<p><strong>' . get_class($e) . ':</strong> ' . e($e->getMessage()) . '</p>';
            echo '<p><strong>File:</strong> ' . e($e->getFile()) . ':' . $e->getLine() . '</p>';
            echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
        } else {
            $this->response->error(500, 'Bir hata oluştu');
        }
    }

    /**
     * Clone engelle (Singleton)
     */
    private function __clone() {}

    /**
     * Unserialize engelle (Singleton)
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}