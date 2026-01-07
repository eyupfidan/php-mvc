<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class PostController extends Controller
{
    public function index(): void
    {
        echo "<h1>Post Listesi</h1><a href='/'>← Ana Sayfa</a>";
    }

    public function create(): void
    {
        echo "<h1>Yeni Post</h1>";
    }

    public function store(): void
    {
        echo "Post kaydedildi";
    }

    public function show(string $id): void
    {
        echo "<h1>Post #{$id}</h1>";
        echo "<p>Parametreli route çalışıyor!</p>";
        echo "<a href='/posts'>← Listele</a>";
    }

    public function edit(string $id): void
    {
        echo "<h1>Post #{$id} Düzenle</h1>";
    }

    public function update(string $id): void
    {
        echo "Post #{$id} güncellendi";
    }

    public function destroy(string $id): void
    {
        echo "Post #{$id} silindi";
    }
}