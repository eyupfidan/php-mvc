
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4">Mini MVC Framework</h1>
                <p class="lead text-muted">PHP 8.2 + MySQL ile basit ve güvenli MVC yapısı</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">✅ Sistem Durumu</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>PHP Versiyon</td>
                            <td><strong><?= phpversion() ?></strong></td>
                        </tr>
                        <tr>
                            <td>Framework</td>
                            <td><strong><?= e(env('APP_NAME')) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Debug Modu</td>
                            <td>
                                <?php if (env('DEBUG')): ?>
                                    <span class="badge bg-warning">Açık</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Kapalı</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Session</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">🔗 Hızlı Linkler</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('/login') ?>" class="btn btn-primary">Giriş Yap</a>
                        <a href="<?= base_url('/register') ?>" class="btn btn-outline-primary">Kayıt Ol</a>
                        <a href="<?= base_url('/posts') ?>" class="btn btn-outline-secondary">Postlar</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">📋 Özellikler</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li>✅ PSR-4 Autoloading</li>
                        <li>✅ CRUD (GET/POST/PUT/DELETE)</li>
                        <li>✅ Middleware Desteği</li>
                        <li>✅ View + Layout Sistemi</li>
                        <li>✅ Flash Mesajlar</li>
                        <li>✅ XSS Koruması</li>
                        <li>✅ CSRF Koruması</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

