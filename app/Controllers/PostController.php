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
            $this->flash('error', 'Post bulunamadı.');
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
        $validator = new Validator($this->request->all());

        if (!$validator->validate([
            'title' => 'required|min:3|max:200',
            'body' => 'required|min:10'
        ])) {
            $this->flash('errors', $validator->errors());
            $this->old($this->request->all());
            $this->redirect('/posts/create');
        }

        Post::create([
            'title' => $this->request->post('title'),
            'body' => $this->request->post('body'),
            'user_id' => Auth::id()
        ]);

        $this->flash('success', 'Post başarıyla oluşturuldu.');
        $this->redirect('/posts');
    }

    public function edit(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $this->flash('error', 'Post bulunamadı.');
            $this->redirect('/posts');
        }

        if (!$this->canEdit($post)) {
            $this->flash('error', 'Bu postu düzenleme yetkiniz yok.');
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
            $this->flash('error', 'Post bulunamadı.');
            $this->redirect('/posts');
        }

        if (!$this->canEdit($post)) {
            $this->flash('error', 'Bu postu düzenleme yetkiniz yok.');
            $this->redirect('/posts');
        }

        $validator = new Validator($this->request->all());

        if (!$validator->validate([
            'title' => 'required|min:3|max:200',
            'body' => 'required|min:10'
        ])) {
            $this->flash('errors', $validator->errors());
            $this->old($this->request->all());
            $this->redirect('/posts/' . $id . '/edit');
        }

        $post->update([
            'title' => $this->request->post('title'),
            'body' => $this->request->post('body')
        ]);

        $this->flash('success', 'Post başarıyla güncellendi.');
        $this->redirect('/posts/' . $id);
    }

    public function destroy(string $id): void
    {
        $post = Post::find((int) $id);

        if (!$post) {
            $this->flash('error', 'Post bulunamadı.');
            $this->redirect('/posts');
        }

        if (!$this->canEdit($post)) {
            $this->flash('error', 'Bu postu silme yetkiniz yok.');
            $this->redirect('/posts');
        }

        $post->delete();

        $this->flash('success', 'Post başarıyla silindi.');
        $this->redirect('/posts');
    }

    private function canEdit(Post $post): bool
    {
        return $post->belongsTo(Auth::id()) || Auth::isAdmin();
    }
}