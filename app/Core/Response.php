<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    /**
     * Status code ayarla
     */
    public function status(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Header ekle
     */
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Body ayarla
     */
    public function body(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    /**
     * Response gönder
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }

    /**
     * HTML response
     */
    public function html(string $content, int $status = 200): void
    {
        $this->status($status)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->body($content)
            ->send();
    }

    /**
     * JSON response
     */
    public function json(mixed $data, int $status = 200): void
    {
        $this->status($status)
            ->header('Content-Type', 'application/json')
            ->body(json_encode($data, JSON_UNESCAPED_UNICODE))
            ->send();
        exit;
    }

    /**
     * Redirect
     */
    public function redirect(string $url, int $status = 302): never
    {
        // Tam URL değilse base_url ekle
        if (!str_starts_with($url, 'http')) {
            $url = base_url($url);
        }

        http_response_code($status);
        header("Location: {$url}");
        exit;
    }

    /**
     * Önceki sayfaya dön
     */
    public function back(): never
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url('/');
        $this->redirect($referer);
    }

    /**
     * Hata sayfası
     */
    public function error(int $code, string $message = ''): void
    {
        $messages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];

        $message = $message ?: ($messages[$code] ?? 'Error');

        $this->status($code)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->body($this->errorTemplate($code, $message))
            ->send();
        exit;
    }

    /**
     * Basit hata template'i
     */
    private function errorTemplate(int $code, string $message): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$code} - {$message}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
               display: flex; align-items: center; justify-content: center; 
               min-height: 100vh; margin: 0; background: #f5f5f5; }
        .error { text-align: center; }
        .error h1 { font-size: 72px; margin: 0; color: #333; }
        .error p { font-size: 24px; color: #666; margin: 10px 0 30px; }
        .error a { color: #007bff; text-decoration: none; }
        .error a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error">
        <h1>{$code}</h1>
        <p>{$message}</p>
        <a href="/">← Ana Sayfa</a>
    </div>
</body>
</html>
HTML;
    }
}