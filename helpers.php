<?php

declare(strict_types=1);


if (!function_exists('e')) {
    /**
     * HTML escape - XSS koruması
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('env')) {
    /**
     * Environment değişkeni oku
     */
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $default;

        // Boolean dönüşümü
        if (is_string($value)) {
            return match (strtolower($value)) {
                'true', '(true)' => true,
                'false', '(false)' => false,
                'null', '(null)' => null,
                default => $value
            };
        }

        return $value;
    }
}

if (!function_exists('base_url')) {
    /**
     * Base URL oluştur
     */
    function base_url(string $path = ''): string
    {
        $base = rtrim($_ENV['BASE_URL'] ?? '', '/');
        $path = ltrim($path, '/');

        return $path ? "{$base}/{$path}" : $base;
    }
}

if (!function_exists('redirect')) {
    /**
     * Yönlendirme yap
     */
    function redirect(string $url): never
    {
        // Tam URL değilse base_url ekle
        if (!str_starts_with($url, 'http')) {
            $url = base_url($url);
        }

        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Eski form değerini al (validation hatası sonrası)
     */
    function old(string $key, string $default = ''): string
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * CSRF token al
     */
    function csrf_token(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * CSRF hidden input alanı
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('method_field')) {
    /**
     * Method spoofing için hidden input
     */
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die - sadece debug için
     */
    function dd(mixed ...$vars): never
    {
        if (!env('DEBUG', false)) {
            exit;
        }

        echo '<pre style="background:#1e1e1e;color:#fff;padding:15px;margin:10px;border-radius:5px;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n---\n";
        }
        echo '</pre>';
        exit;
    }
}