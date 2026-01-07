<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * View render et
     */
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    /**
     * JSON response
     */
    protected function json(mixed $data, int $status = 200): void
    {
        (new Response())->json($data, $status);
    }

    /**
     * Redirect
     */
    protected function redirect(string $url): never
    {
        (new Response())->redirect($url);
    }

    /**
     * Önceki sayfaya dön
     */
    protected function back(): never
    {
        (new Response())->back();
    }
}