<?php
use App\Core\Auth;
use App\Models\User;

$author = User::find($post->user_id);
$canEdit = $post->belongsTo(Auth::id()) || Auth::isAdmin();
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/posts') ?>">Postlar</a></li>
                <li class="breadcrumb-item active"><?= e($post->title) ?></li>
            </ol>
        </nav>

        <article class="card">
            <div class="card-body">
                <h1 class="card-title mb-3"><?= e($post->title) ?></h1>

                <div class="text-muted mb-4">
                    <span>Yazar: <strong><?= e($author?->name ?? 'Bilinmiyor') ?></strong></span>
                    <span class="mx-2">|</span>
                    <span><?= date('d.m.Y H:i', strtotime($post->created_at)) ?></span>
                </div>

                <div class="card-text">
                    <?= nl2br(e($post->body)) ?>
                </div>
            </div>

            <?php if ($canEdit): ?>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('/posts/' . $post->id . '/edit') ?>" class="btn btn-warning btn-sm">
                            Düzenle
                        </a>
                        <form action="<?= base_url('/posts/' . $post->id) ?>" method="POST"
                              onsubmit="return confirm('Bu postu silmek istediğinize emin misiniz?')">
                            <?= csrf_field() ?>
                            <?= method_field('DELETE') ?>
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </article>

        <div class="mt-4">
            <a href="<?= base_url('/posts') ?>" class="btn btn-secondary">← Tüm Postlar</a>
        </div>
    </div>
</div>