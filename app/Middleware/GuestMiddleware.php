<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

class GuestMiddleware
{
    public function handle(array $params = []): bool
    {
        if (Auth::check()) {
            header('Location: ' . base_url('/posts'));
            return false;
        }

        return true;
    }
}