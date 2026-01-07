<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $query;
    private array $post;
    private array $cookies;
    private array $files;
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->query = $_GET;
        $this->post = $_POST;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;

        $this->method = $this->resolveMethod();
        $this->uri = $this->resolveUri();
    }

    /**
     * HTTP method'u çöz (spoofing dahil)
     */
    private function resolveMethod(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';

        // Method spoofing (PUT/DELETE için)
        if ($method === 'POST' && isset($this->post['_method'])) {
            $spoofed = strtoupper($this->post['_method']);
            if (in_array($spoofed, ['PUT', 'PATCH', 'DELETE'])) {
                return $spoofed;
            }
        }

        return $method;
    }

    /**
     * URI'yi çöz
     */
    private function resolveUri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $basePath = dirname($this->server['SCRIPT_NAME']);

        // Base path'i temizle
        if ($basePath !== '/' && $basePath !== '\\') {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        // PATH_INFO varsa kullan
        if (isset($this->server['PATH_INFO'])) {
            $uri = $this->server['PATH_INFO'];
        }

        // Query string'i ayır
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';

        return '/' . trim($uri, '/');
    }

    /**
     * HTTP method
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Request URI
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * GET parametresi
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * POST parametresi
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * GET veya POST'tan al
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Tüm input'ları al
     */
    public function all(): array
    {
        return array_merge($this->query, $this->post);
    }

    /**
     * Sadece belirli alanları al
     */
    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($this->post[$key]) || isset($this->query[$key])) {
                $result[$key] = $this->input($key);
            }
        }
        return $result;
    }

    /**
     * Belirli alanlar hariç al
     */
    public function except(array $keys): array
    {
        $all = $this->all();
        foreach ($keys as $key) {
            unset($all[$key]);
        }
        return $all;
    }

    /**
     * Input var mı?
     */
    public function has(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->query[$key]);
    }

    /**
     * Cookie al
     */
    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Dosya al
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Server değişkeni al
     */
    public function server(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * AJAX request mi?
     */
    public function isAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    /**
     * Güvenli bağlantı mı?
     */
    public function isSecure(): bool
    {
        return ($this->server['HTTPS'] ?? '') === 'on';
    }

    /**
     * Client IP
     */
    public function ip(): string
    {
        return $this->server['HTTP_X_FORWARDED_FOR']
            ?? $this->server['REMOTE_ADDR']
            ?? '0.0.0.0';
    }
}