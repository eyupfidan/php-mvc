<?php

declare(strict_types=1);

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(array $params = []): bool
    {
        return true;
    }
}