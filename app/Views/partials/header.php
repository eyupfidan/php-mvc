<?php
use App\Core\Session;

$user = Session::get('user');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">
            <?= e(env('APP_NAME')) ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/') ?>">Ana Sayfa</a>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/posts') ?>">Postlar</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <?php if ($user): ?>
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <?= e($user['name']) ?>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="<?= base_url('/logout') ?>" method="POST" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-light btn-sm">Çıkış</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/login') ?>">Giriş</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/register') ?>">Kayıt Ol</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>