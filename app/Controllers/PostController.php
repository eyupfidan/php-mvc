<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Validator;
use App\Models\Post;

class PostController extends Controller
{
    public function index(): void
    {
        $posts = Post::all();

        $this->view('posts/index', [
            'title' => 'Postlar',
            'posts' => $posts
        ]);
    }

    public function show(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $_SESSION['_flash']['error'] = 'Post bulunamadı.';
            $this->redirect('/posts');
        }

        $this->view('posts/show', [
            'title' => $post->title,
            'post' => $post
        ]);
    }

    public function create(): void
    {
        $this->view('posts/create', [
            'title' => 'Yeni Post'
        ]);
    }

    public function store(): void
    {
        $validator = new Validator($_POST);

        if (!$validator->validate([
            'title' => 'required|min:3|max:200',
            'body' => 'required|min:10'
        ])) {
            $_SESSION['_flash']['errors'] = $validator->errors();
            $_SESSION['_old'] = $_POST;
            $this->redirect('/posts/create');
        }

        Post::create([
            'title' => $_POST['title'],
            'body' => $_POST['body'],
            'user_id' => Auth::id()
        ]);

        $_SESSION['_flash']['success'] = 'Post başarıyla oluşturuldu.';
        $this->redirect('/posts');
    }

    public function edit(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $_SESSION['_flash']['error'] = 'Post bulunamadı.';
            $this->redirect('/posts');
        }

        // Yetki kontrolü: Sadece sahibi veya admin
        if (!$this->canEdit($post)) {
            $_SESSION['_flash']['error'] = 'Bu postu düzenleme yetkiniz yok.';
            $this->redirect('/posts');
        }

        $this->view('posts/edit', [
            'title' => 'Post Düzenle',
            'post' => $post
        ]);
    }

    public function update(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $_SESSION['_flash']['error'] = 'Post bulunamadı.';
            $this->redirect('/posts');
        }

        if (!$this->canEdit($post)) {
            $_SESSION['_flash']['error'] = 'Bu postu düzenleme yetkiniz yok.';
            $this->redirect('/posts');
        }

        $validator = new Validator($_POST);

        if (!$validator->validate([
            'title' => 'required|min:3|max:200',
            'body' => 'required|min:10'
        ])) {
            $_SESSION['_flash']['errors'] = $validator->errors();
            $_SESSION['_old'] = $_POST;
            $this->redirect('/posts/' . $id . '/edit');
        }

        $post->update([
            'title' => $_POST['title'],
            'body' => $_POST['body']
        ]);

        $_SESSION['_flash']['success'] = 'Post başarıyla güncellendi.';
        $this->redirect('/posts/' . $id);
    }

    public function destroy(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $_SESSION['_flash']['error'] = 'Post bulunamadı.';
            $this->redirect('/posts');
        }

        if (!$this->canEdit($post)) {
            $_SESSION['_flash']['error'] = 'Bu postu silme yetkiniz yok.';
            $this->redirect('/posts');
        }

        $post->delete();

        $_SESSION['_flash']['success'] = 'Post başarıyla silindi.';
        $this->redirect('/posts');
    }

    /**
     * Düzenleme/silme yetkisi var mı?
     */
    private function canEdit(Post $post): bool
    {
        return $post->belongsTo(Auth::id()) || Auth::isAdmin();
    }
}