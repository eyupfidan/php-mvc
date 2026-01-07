<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * View render et
     */
    public static function render(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View bulunamadı: {$view}");
        }

        extract($data);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        require dirname(__DIR__) . '/Views/layouts/app.php';
    }
}