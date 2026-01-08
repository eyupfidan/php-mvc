<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function json(mixed $data, int $status = 200): never
    {
        $this->response->json($data, $status);
    }

    protected function redirect(string $url): never
    {
        $this->response->redirect($url);
    }

    protected function back(): never
    {
        $this->response->back();
    }

    protected function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    protected function old(array $data): void
    {
        $_SESSION['_old'] = $data;
    }
}