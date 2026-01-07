<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware
{
    public function handle(array $params = []): bool
    {
        if (!Auth::check()) {
            $_SESSION['_flash']['error'] = 'Bu sayfayı görüntülemek için giriş yapmalısınız.';
            header('Location: ' . base_url('/login'));
            return false;
        }

        return true;
    }
}