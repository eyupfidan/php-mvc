<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Postlar</h1>
    <a href="<?= base_url('/posts/create') ?>" class="btn btn-primary">+ Yeni Post</a>
</div>

<?php if (empty($posts)): ?>
    <div class="alert alert-info">
        Henüz post yok. <a href="<?= base_url('/posts/create') ?>">İlk postu oluşturun!</a>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= e($post['title']) ?></h5>
                        <p class="card-text text-muted">
                            <?= e(mb_substr($post['body'], 0, 150)) ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                            </small>
                            <a href="<?= base_url('/posts/' . $post['id']) ?>" class="btn btn-sm btn-outline-primary">
                                Oku →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>