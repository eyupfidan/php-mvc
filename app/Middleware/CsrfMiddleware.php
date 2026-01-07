<?php

declare(strict_types=1);

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(array $params = []): bool
    {
        $tokenFromForm = $_POST['_token'] ?? '';
        $tokenFromSession = $_SESSION['_csrf_token'] ?? '';

        if (empty($tokenFromSession) || !hash_equals($tokenFromSession, $tokenFromForm)) {
            http_response_code(403);
            echo '<h1>403 - Geçersiz CSRF Token</h1>';
            echo '<p>Form süresi dolmuş olabilir. <a href="javascript:history.back()">Geri dön</a> ve sayfayı yenileyin.</p>';
            return false;
        }

        return true;
    }
}