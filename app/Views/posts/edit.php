<div class="row">
    <div class="col-lg-8 mx-auto">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/posts') ?>">Postlar</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('/posts/' . $post->id) ?>"><?= e($post->title) ?></a></li>
                <li class="breadcrumb-item active">Düzenle</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Post Düzenle</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('/posts/' . $post->id) ?>">
                    <?= csrf_field() ?>
                    <?= method_field('PUT') ?>

                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text"
                               class="form-control"
                               id="title"
                               name="title"
                               value="<?= e($_SESSION['_old']['title'] ?? $post->title) ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">İçerik</label>
                        <textarea class="form-control"
                                  id="body"
                                  name="body"
                                  rows="8"
                                  required><?= e($_SESSION['_old']['body'] ?? $post->body) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="<?= base_url('/posts/' . $post->id) ?>" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php unset($_SESSION['_old']); ?>