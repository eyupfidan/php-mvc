<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class RoleMiddleware
{
    public function handle(array $params = []): bool
    {
        $requiredRole = $params[0] ?? null;

        if (!$requiredRole) {
            return true;
        }

        if (!Auth::check()) {
            $_SESSION['_flash']['error'] = 'Giriş yapmalısınız.';
            header('Location: ' . base_url('/login'));
            return false;
        }

        $userRole = Auth::user()['role'] ?? '';

        // Admin her şeye erişebilir
        if ($userRole === 'admin') {
            return true;
        }

        if ($userRole !== $requiredRole) {
            $_SESSION['_flash']['error'] = 'Bu işlem için yetkiniz yok.';
            header('Location: ' . base_url('/posts'));
            return false;
        }

        return true;
    }
}