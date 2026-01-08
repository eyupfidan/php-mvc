<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function redirect(string $url): never
    {
        if (!str_starts_with($url, 'http')) {
            $url = base_url($url);
        }
        header("Location: {$url}");
        exit;
    }

    public function back(): never
    {
        $url = $_SERVER['HTTP_REFERER'] ?? base_url('/');
        header("Location: {$url}");
        exit;
    }

    public function error(int $code, string $message = ''): never
    {
        http_response_code($code);
        echo "<h1>{$code}</h1><p>{$message}</p>";
        exit;
    }
}